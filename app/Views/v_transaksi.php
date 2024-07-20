<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Menampilkan pesan sukses atau error jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between mb-2">
    <h4>Daftar Transaksi</h4>
    <?php if (session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('transaksi/downloadPdf') ?>" class="btn btn-primary">Download PDF</a>
    <?php endif; ?>
</div>
<hr>

<div class="table-responsive">
    <!-- Table with stripped rows -->
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Total Harga</th>
                <th scope="col">Alamat</th>
                <th scope="col">Ongkir</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($transaksi)) :
                foreach ($transaksi as $index => $item) :
            ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $item['username'] ?></td>
                        <td><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
                        <td><?= $item['alamat'] ?></td>
                        <td><?= number_to_currency($item['ongkir'], 'IDR') ?></td>
                        <td><?= $item['status'] == "1" ? "1" : "0" ?></td>
                        <td>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                                Ubah Status
                            </button>
                        </td>
                    </tr>
                    <!-- Modal untuk Ubah Status -->
                    <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?= $item['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel-<?= $item['id'] ?>">Ubah Status Transaksi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?= base_url('transaksi/updateStatus') ?>" method="post">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="0" <?= $item['status'] == '0' ? 'selected' : '' ?>>0</option>
                                                <option value="1" <?= $item['status'] == '1' ? 'selected' : '' ?>>1</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ubah Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            else:
            ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
    <!-- End Table with stripped rows -->
</div>

<?= $this->endSection() ?>
