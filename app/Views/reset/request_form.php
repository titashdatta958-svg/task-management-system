<?= view('layout/header') ?>
<style>
    .reset-wrapper {
        max-width: 480px;
        margin: 70px auto;
        padding: 0;
        font-family: "Inter", sans-serif;
    }

    .reset-card {
        background: linear-gradient(135deg, #ffffff 0%, #f7f9fc 100%);
        border: 1px solid #e0e6ef;
        border-radius: 14px;
        padding: 40px 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .reset-card h2 {
        font-size: 26px;
        font-weight: 700;
        color: #1a1f36;
        text-align: center;
        margin-bottom: 25px;
        letter-spacing: -0.3px;
    }

    .label {
        font-size: 15px;
        font-weight: 600;
        color: #2a2f45;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        background: #fdfdfd;
        border: 1px solid #cfd6e1;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 15px;
        transition: 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #4f8cff;
        box-shadow: 0px 0px 0px 3px rgba(79,140,255,0.25);
        background: #fff;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #4f8cff 0%, #3a6be0 100%);
        border: none;
        padding: 13px;
        border-radius: 10px;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(79,140,255,0.3);
    }
</style>


<div class="reset-wrapper">
    <div class="reset-card">

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>


        <h2>Request Password Reset</h2>

        <form action="<?= base_url('reset-request'); ?>" method="POST">

            <label class="label">Email</label>
            <input type="email" name="email" required class="form-control">

            <!-- <label class="label mt-3">Reason for Reset</label>
            <textarea name="reason" class="form-control" required></textarea> -->

            <button type="submit" class="btn-submit">Submit Request</button>
        </form>

    </div>
</div>
