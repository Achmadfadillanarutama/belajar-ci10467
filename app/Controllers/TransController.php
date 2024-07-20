<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use Dompdf\Dompdf;

class TransController extends BaseController
{
    protected $cart;
    protected $url = "https://api.rajaongkir.com/starter/";
    protected $apiKey = "ae4b0421f38dd6cd9ae8bc74a55b76e1";
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        helper('number');
        helper('form');
        $this->cart = \Config\Services::cart();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    // Method untuk menampilkan daftar transaksi
    public function index()
    {
        $data['transaksi'] = $this->transaction->findAll();
        return view('v_transaksi', $data); // Update view file name here
    }

    // Method untuk mengupdate status transaksi
    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if ($id && $status !== null) {
            $this->transaction->update($id, ['status' => $status]);
            session()->setFlashdata('success', 'Status transaksi berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui status transaksi.');
        }

        return redirect()->to('/transaksi');
    }

    // Method untuk mengunduh data transaksi dalam format PDF
    public function downloadPdf()
    {
        $transaksi = $this->transaction->findAll();

        $dompdf = new Dompdf();
        $html = view('pdf_transaksi', ['transaksi' => $transaksi]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("transaksi.pdf", array("Attachment" => 1));
    }

    // Method untuk checkout, mendapatkan kota, biaya pengiriman, dan lainnya tetap sama
    public function checkout()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        $provinsi = $this->rajaongkir('province');
        $data['provinsi'] = json_decode($provinsi)->rajaongkir->results;

        return view('v_checkout', $data);
    }

    public function getCity()
    {
        if ($this->request->isAJAX()) {
            $id_province = $this->request->getGet('id_province');
            $data = $this->rajaongkir('city', $id_province);
            return $this->response->setJSON($data);
        }
    }

    public function getCost()
    {
        if ($this->request->isAJAX()) {
            $origin = $this->request->getGet('origin');
            $destination = $this->request->getGet('destination');
            $weight = $this->request->getGet('weight');
            $courier = $this->request->getGet('courier');
            $data = $this->rajaongkircost($origin, $destination, $weight, $courier);
            return $this->response->setJSON($data);
        }
    }

    private function rajaongkircost($origin, $destination, $weight, $courier)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . $origin . "&destination=" . $destination . "&weight=" . $weight . "&courier=" . $courier,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->apiKey,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function rajaongkir($method, $id_province = null)
    {
        $endPoint = $this->url . $method;

        if ($id_province != null) {
            $endPoint = $endPoint . "?province=" . $id_province;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function buy()
    {
        if ($this->request->getPost()) { 
            $dataForm = [
                'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            if ($this->transaction->insert($dataForm)) {
                $last_insert_id = $this->transaction->getInsertID();

                foreach ($this->cart->contents() as $value) {
                    $dataFormDetail = [
                        'transaction_id' => $last_insert_id,
                        'product_id' => $value['id'],
                        'jumlah' => $value['qty'],
                        'diskon' => 0,
                        'subtotal_harga' => $value['qty'] * $value['price'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];

                    $this->transaction_detail->insert($dataFormDetail);
                }

                $this->cart->destroy();
                session()->setFlashdata('success', 'Transaksi berhasil.');
            } else {
                session()->setFlashdata('error', 'Gagal melakukan transaksi.');
            }

            return redirect()->to(base_url('profile'));
        }
    }
}
