<?= view('layout/header') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #ff8800ff;
            padding: 40px;
        }

        h1 {
            color: #000;
            margin-bottom: 20px;
        }

        .form-box {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.15);
            max-width: 600px;
        }

        label {
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background-color: #28a745;
            padding: 10px 18px;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #000000ff;
            padding: 10px 18px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-left: 10px;
            transition: 0.3s;
        }

        .btn-back:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        .error-box {
            background: #ffdddd;
            border-left: 4px solid #ff3b3b;
            padding: 10px;
            color: #b30000;
            margin-bottom: 20px;
            border-radius: 6px;
        }

    </style>
</head>

<body>

<h1>Edit Project</h1>

<div class="form-box">

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="error-box">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>



    <form action="/projects/update/<?= $project['id'] ?>" method="post">
    <?= csrf_field() ?>

    <label>Name</label>
    <input type="text" name="name" value="<?= esc(old('name', $project['name'])) ?>" required>
    <?php if(isset(session()->getFlashdata('errors')['name'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['name']) ?></div>
    <?php endif; ?>

    <label>Description</label>
    <textarea name="description" required><?= esc(old('description', $project['description'])) ?></textarea>
    <?php if(isset(session()->getFlashdata('errors')['description'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['description']) ?></div>
    <?php endif; ?>

    <label>Start Date</label>
    <input type="date" name="start_date" required min="<?= date('Y-m-d') ?>" value="<?= old('start_date', $project['start_date']) ?>">

    <?php if(isset(session()->getFlashdata('errors')['start_date'])): ?>
        <div class="error-box"><?= esc(session()->getFlashdata('errors')['start_date']) ?></div>
    <?php endif; ?>

    <label>End Date</label>
    <input type="date" name="end_date" required min="<?= date('Y-m-d') ?>"  value="<?= old('end_date', $project['end_date']) ?>" >
  

    <button type="submit" class="btn-submit">Update</button>
    <a href="/projects" class="btn-back">Back</a>
</form>



</div>

</body>
</html>
