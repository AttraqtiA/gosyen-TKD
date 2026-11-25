<?php

namespace App\Http\Controllers;

use App\Enums\ExamSession;
use App\Models\JawabanSesi2;
use App\Models\JawabanSesi3;
use App\Models\JawabanSesi4;
use App\Models\JawabanSesi5;
use App\Models\JawabanSesi6;
use App\Models\Perusahaan;
use App\Models\PesertaUji;
use App\Models\Posisi;
use App\Services\ExamStateMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UjianController extends Controller
{
    public function __construct(
        private readonly ExamStateMachine $examState = new ExamStateMachine()
    ) {}

    public function index()
    {
        $this->examState->clearAttemptSession();

        $posisiList = Posisi::orderBy('nama', 'asc')->get();
        $perusahaanList = Schema::hasTable('perusahaan')
            ? Perusahaan::orderBy('nama', 'asc')->get()
            : collect([
                (object) ['id' => null, 'nama' => 'PT. GOSYEN POLINATOR INDONESIA'],
                (object) ['id' => null, 'nama' => 'PT. MAJU ANUGRAH JAYA UNGGUL'],
                (object) ['id' => null, 'nama' => 'PT. PANEN ANUGERAH NUSINDO'],
                (object) ['id' => null, 'nama' => 'PT DWI TUNGGAL MULIA KIMIA'],
            ]);

        return view('ujian.index', compact('posisiList', 'perusahaanList'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'posisi' => 'required|string',
            'perusahaan' => 'required|string',
        ]);

        $posisiVal = $request->posisi;
        if ($posisiVal === 'Lainnya') {
            $request->validate([
                'posisi_lainnya' => 'required|string|max:150',
            ]);
            $posisiVal = $request->posisi_lainnya;
        } else {
            $request->validate([
                'posisi' => 'required|string|exists:posisi,nama',
            ]);
        }

        $perusahaanVal = $request->perusahaan;
        if ($perusahaanVal === 'Lainnya') {
            $request->validate([
                'perusahaan_lainnya' => 'required|string|max:150',
            ]);
            $perusahaanVal = $request->perusahaan_lainnya;
        }

        if (
            $request->posisi !== 'Lainnya'
            && $request->perusahaan !== 'Lainnya'
            && Schema::hasTable('perusahaan')
            && Schema::hasColumn('posisi', 'perusahaan_id')
        ) {
            $company = Perusahaan::where('nama', $perusahaanVal)->first();
            $posisiObj = Posisi::where('nama', $posisiVal)->first();
            if ($company && $posisiObj && $posisiObj->perusahaan_id !== null && (int) $posisiObj->perusahaan_id !== (int) $company->id) {
                return redirect()->back()->withInput()->withErrors([
                    'posisi' => __('Posisi yang dipilih tidak tersedia untuk perusahaan ini.')
                ]);
            }
        }

        $this->examState->start($request->nama, $posisiVal, $perusahaanVal);

        return redirect()->route('ujian.petunjuk', ['sesi' => ExamSession::WA->value]);
    }

    public function petunjuk($sesi)
    {
        if ($redirect = $this->examState->guardInstructions((int) $sesi)) {
            return $redirect;
        }

        $sessionEnum = ExamSession::from((int) $sesi);
        $isCustomPosisi = !$this->examState->hasSesi5();
        $totalSessions = $this->examState->totalSessionsFor();
        $isLastSession = $this->examState->isLastSession($sessionEnum);
        $durasiMenit = $sessionEnum->durationMinutes();

        return view('ujian.petunjuk', compact(
            'sesi',
            'isCustomPosisi',
            'totalSessions',
            'isLastSession',
            'durasiMenit'
        ));
    }

    public function showSesi($sesi)
    {
        if ($redirect = $this->examState->enterSession((int) $sesi)) {
            return $redirect;
        }

        $sessionEnum = ExamSession::from((int) $sesi);
        $durasiMenit = $sessionEnum->durationMinutes();
        $durasi = $this->examState->remainingSeconds($sessionEnum);
        $isCustomPosisi = !$this->examState->hasSesi5();
        $totalSessions = $this->examState->totalSessionsFor();
        $isLastSession = $this->examState->isLastSession($sessionEnum);
        $criticalThreshold = $sessionEnum->criticalThresholdSeconds();

        $sharedMeta = compact(
            'durasi',
            'durasiMenit',
            'isCustomPosisi',
            'totalSessions',
            'isLastSession',
            'criticalThreshold'
        );

        return match ($sessionEnum) {
            ExamSession::WA => view($sessionEnum->viewName(), array_merge($sharedMeta, [
                'soal' => $this->getOrBuildSesi1Questions(),
            ])),
            ExamSession::AN => view($sessionEnum->viewName(), array_merge($sharedMeta, [
                'soal' => DB::table('bank_soal_sesi3')->orderBy('id_soal', 'asc')->get(),
            ])),
            ExamSession::ZR => view($sessionEnum->viewName(), array_merge($sharedMeta, [
                'soal' => DB::table('bank_soal_sesi4')->orderBy('id_soal', 'asc')->get(),
            ])),
            ExamSession::FA => view($sessionEnum->viewName(), array_merge($sharedMeta, [
                'soalSesi4' => $this->getSesi4Images(),
            ])),
            ExamSession::ESSAY => view($sessionEnum->viewName(), array_merge($sharedMeta, [
                'posisi' => session('posisi'),
                'soalSesi5' => $this->translateSesi5Array($this->getSoalSesi5(session('posisi')), session('posisi')),
            ])),
        };
    }

    public function logPelanggaran(Request $request)
    {
        if (!$this->examState->hasActiveAttempt()) {
            return response()->json(['ok' => false], 401);
        }

        return response()->json($this->examState->recordViolation());
    }

    public function submitSesi(Request $request, $sesi)
    {
        if ($redirect = $this->examState->guardAndSyncSubmit((int) $sesi, (int) $request->input('pelanggaran_sesi', 0))) {
            return $redirect;
        }

        $sessionEnum = ExamSession::from((int) $sesi);

        match ($sessionEnum) {
            ExamSession::WA => $this->storeSesi1Answers($request),
            ExamSession::AN, ExamSession::ZR, ExamSession::FA, ExamSession::ESSAY => $this->storeSequentialAnswers($request, $sessionEnum),
        };

        return $this->examState->advanceAfterSubmit($sessionEnum);
    }

    public function simpan()
    {
        if ($redirect = $this->examState->guardSave()) {
            return $redirect;
        }

        try {
            DB::beginTransaction();

            $peserta = PesertaUji::create([
                'nama' => session('nama'),
                'posisi' => session('posisi'),
                'perusahaan' => session('perusahaan', '-'),
                'total_pelanggaran' => $this->examState->totalViolations(),
            ]);

            $idPeserta = $peserta->id_peserta;

            JawabanSesi2::create($this->extractAnswersPayload($idPeserta, ExamSession::WA));
            JawabanSesi3::create($this->extractAnswersPayload($idPeserta, ExamSession::AN));
            JawabanSesi4::create($this->extractAnswersPayload($idPeserta, ExamSession::ZR));
            JawabanSesi5::create($this->extractAnswersPayload($idPeserta, ExamSession::FA));
            JawabanSesi6::create($this->extractAnswersPayload($idPeserta, ExamSession::ESSAY));

            DB::commit();

            $nama = session('nama');
            $this->examState->clearAttemptSession();

            return view('ujian.selesai', [
                'status_sukses' => true,
                'nama' => $nama,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return view('ujian.selesai', [
                'status_sukses' => false,
                'error_msg' => $e->getMessage(),
            ]);
        }
    }

    public function getSoalSesi5($posisi)
    {
        if (!Schema::hasTable('bank_soal_sesi5')) {
            return [
                'bagian_a' => [],
                'bagian_b' => [],
            ];
        }

        $rows = DB::table('bank_soal_sesi5')
            ->where('posisi', $posisi)
            ->orderBy('id', 'asc')
            ->get();

        $soalSesi5 = [
            'bagian_a' => [],
            'bagian_b' => [],
        ];

        $qCount = 1;
        foreach ($rows as $row) {
            $pertanyaan = json_decode($row->pertanyaan, true) ?: [];
            if ($row->tipe === 'essay') {
                foreach ($pertanyaan as $qText) {
                    $soalSesi5['bagian_a'][$qCount] = $qText;
                    $qCount++;
                }
            } elseif ($row->tipe === 'studi_kasus') {
                $pertanyaanIndexed = [];
                $subCount = 1;
                foreach ($pertanyaan as $subQText) {
                    $pertanyaanIndexed[$subCount] = $subQText;
                    $subCount++;
                }

                $soalSesi5['bagian_b'][] = [
                    'judul' => $row->judul,
                    'deskripsi' => $row->deskripsi,
                    'pertanyaan' => $pertanyaanIndexed,
                ];
            }
        }

        return $soalSesi5;
    }

    public function translateSesi5Array($data, $posisi)
    {
        if (app()->getLocale() !== 'en') {
            return $data;
        }

        $jsonPath = base_path('lang/en_sesi5.json');
        if (file_exists($jsonPath)) {
            $enData = json_decode(file_get_contents($jsonPath), true);
            if (isset($enData[$posisi])) {
                return $enData[$posisi];
            }
        }

        return $data;
    }

    private function getOrBuildSesi1Questions(): array
    {
        if (session()->has('soal_sesi2')) {
            return session('soal_sesi2');
        }

        $soalRaw = DB::table('bank_soal_sesi2')->inRandomOrder()->limit(20)->get();
        $soalAcak = [];
        $nomorGlobal = 1;

        foreach ($soalRaw as $row) {
            $idSoalAsli = $row->id_soal;
            $opsiAsli = [
                'A' => $row->opsi_a,
                'B' => $row->opsi_b,
                'C' => $row->opsi_c,
                'D' => $row->opsi_d,
                'E' => $row->opsi_e,
            ];

            $kunciOpsi = array_keys($opsiAsli);
            shuffle($kunciOpsi);

            $opsiTampil = [];
            $labelBaru = ['A', 'B', 'C', 'D', 'E'];
            foreach ($kunciOpsi as $idx => $labelLama) {
                $opsiTampil[$labelBaru[$idx]] = [
                    'teks' => $opsiAsli[$labelLama],
                    'asli' => $labelLama,
                ];
            }

            $kunci = DB::table('kunci_jawaban')
                ->where('nama_sesi', 'sesi2')
                ->where('no_soal', $idSoalAsli)
                ->first();

            $soalAcak[] = [
                'no_tampil' => $nomorGlobal,
                'id_asli' => $idSoalAsli,
                'opsi' => $opsiTampil,
                'kunci_asli' => $kunci ? $kunci->jawaban_benar : '',
            ];

            $nomorGlobal++;
        }

        session(['soal_sesi2' => $soalAcak]);

        return $soalAcak;
    }

    private function getSesi4Images(): array
    {
        $images = [];
        for ($i = 1; $i <= 20; $i++) {
            $images[$i] = "gambar/visual_reasoning/{$i}.png";
        }
        return $images;
    }

    private function storeSesi1Answers(Request $request): void
    {
        $soalAcak = session('soal_sesi2', []);
        for ($i = 1; $i <= 20; $i++) {
            session(['jawab_sesi2_q' . $i => '']);
        }

        foreach ($soalAcak as $soal) {
            $noTampil = $soal['no_tampil'];
            $idAsli = $soal['id_asli'];
            $jawaban = $request->input('jawab_sesi2_q' . $noTampil, '');
            session(['jawab_sesi2_q' . $idAsli => $jawaban]);
        }
    }

    private function storeSequentialAnswers(Request $request, ExamSession $session): void
    {
        $prefix = $session->answerSessionPrefix();
        $count = $session->questionCount();
        for ($i = 1; $i <= $count; $i++) {
            session([$prefix . $i => $request->input($prefix . $i, '')]);
        }
    }

    private function extractAnswersPayload(int $idPeserta, ExamSession $session): array
    {
        $payload = ['id_peserta' => $idPeserta];
        $prefix = $session->answerSessionPrefix();
        $count = $session->questionCount();
        for ($i = 1; $i <= $count; $i++) {
            $payload['q' . $i] = session($prefix . $i) ?: null;
        }
        return $payload;
    }
}
