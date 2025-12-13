<?= view('layout/header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #ff8800ff;
            padding: 40px;
        }

        /* Form Card */
        .form-container {
            max-width: 700px;
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            margin-right: 100px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            border-color: #000;
            box-shadow: 0 0 4px rgba(0,0,0,0.3);
        }

        textarea {
            height: 120px;
            resize: none;
        }

        /* Buttons */
        .btn-submit {
            background: #28a745;
            color: #fff;
            padding: 12px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }

        .btn-back {
            text-decoration: none;
            padding: 12px 18px;
            background: #000;
            color: #fff;
            border-radius: 8px;
            margin-left: 10px;
            transition: 0.3s;
        }

        .btn-back:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        .error-box {
            background: #ffdddd;
            padding: 10px;
            border-left: 5px solid #cc0000;
            margin-bottom: 15px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="form-container">
    <h1>Create Project</h1>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="error-box">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


   <form action="/projects/store" method="post">
    <?= csrf_field() ?>

    <label>Name</label>
    <input type="text" name="name" value="<?= old('name') ?>" required>
    <?php if(isset(session()->getFlashdata('errors')['name'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['name']) ?></div>
    <?php endif; ?>

    <label>Description</label>
    <textarea name="description" required><?= old('description') ?></textarea>
    <?php if(isset(session()->getFlashdata('errors')['description'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['description']) ?></div>
    <?php endif; ?>

    <label>Start Date</label>
    <input type="date" name="start_date" required min="<?= date('Y-m-d') ?>">
    <?php if(isset(session()->getFlashdata('errors')['start_date'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['start_date']) ?></div>
    <?php endif; ?>

    <label>End Date</label>
   <input type="date" name="end_date" required min="<?= date('Y-m-d') ?>">
   

    <button type="submit" class="btn-submit">Create</button>
    <a href="/projects" class="btn-back">Back</a>
</form>



</div>

</body>
</html>
<?= view('layout/footer') ?>