<?= view('layout/header') ?>

<style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }

    .form-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: right 100px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
    }

    input[type=text],
    input[type=email] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .btn-submit {
        background: #28a745;
        color: #fff;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
        font-size: 15px;
        transition: .3s;
    }

    .btn-submit:hover {
        opacity: .85;
        transform: translateY(-2px);
    }
</style>

<div class="form-box">
    <h2>Add Member</h2>

    <form action="/members/store" method="post">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <button type="submit" class="btn-submit">Save Member</button>

    </form>
</div>
<?= view('layout/footer') ?>