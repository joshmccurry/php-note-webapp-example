<?php
require 'notes.php';
// Simple Note - A simple note taking application
$notes = new Notes;

// Actions
if (isset($_POST['new'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $notes->create($title, $content);
    header('Location: .');
    exit();
}
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $notes->edit($id, $title, $content);
    header('Location: .');
    exit();
}
if (!empty($_GET['del'])) {
    $id = $_GET['del'];
    $notes->delete($id);
    header('Location: .');
    exit();
}
if (!empty($_GET['dl'])) {
    $id = $_GET['dl'];
    $notes->fetchNotes($id);
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webjob Example Note App</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootswatch/4.5.2/flatly/bootstrap.min.css">
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        textarea {
            resize: vertical; /* allow only vertical stretch */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h2> Send a new note </h2>
        </div>
        <form role="form" action="index.php" method="POST">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Title" name="title" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" rows="5" placeholder="Enter Note..." name="content" autofocus required></textarea>
            </div>
            <div class="btn-group float-right">
                <button class="btn btn-danger" type="reset"><span class="fa fa-times mr-2"></span>Clear</button>
                <button class="btn btn-success" name="new" type="submit"><span class="fa fa-paper-plane mr-2"></span>Send</button>
            </div>
        </form>
    </div>

    <?php
    if (!empty($notes->fetchNotes())):
        $notes = $notes->fetchNotes();
    ?>

    <div class="container my-5 py-5" id="notes">
        <div class="page-header">
            <h2>Recent Notes</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Time</th>
                            <th class="text-right">Date</th>
                            <th class="text-right">Actions<br></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach ($notes as $row): ?>
                            <td>
                                <?= htmlspecialchars(substr($row['title'], 0, 15), ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td class="text-right"><?= date('H:i', strtotime($row['created'])) ?></td>
                            <td class="text-right"><?= date('d/m/Y', strtotime($row['created'])) ?></td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm" title="Edit this note" data-toggle="modal" data-target="#edit<?= $row['ID'] ?>"><span class="fa fa-edit"></span></button>
                                    <a class="btn btn-danger btn-sm" title="Delete this note" onclick="deleteNote(<?= $row['ID'] ?>)"><span class="fa fa-trash-alt"></span></a>
                                    <a class="btn btn-info btn-sm" title="Download this note" href="?dl=<?= $row['ID'] ?>" target="_blank"><span class="fa fa-download"></span></a>
                                </div>
                                <div class="modal fade" id="edit<?= $row['ID'] ?>" tabindex="-1" aria-labelledby="edit<?= $row['ID'] ?>" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit note</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" method="POST" id="edit-form-<?= $row['ID'] ?>">
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" placeholder="Title" name="title" value="<?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="5" placeholder="What do you have in mind?" name="content" required><?= htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                                    </div>
                                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                                    <input type="hidden" name="edit" value="1">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="btn-group pull-right">
                                                    <button class="btn btn-success" name="edit" type="submit" form="edit-form-<?= $row['ID'] ?>">
                                                        <span class="fa fa-save mr-2"></span>
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        function deleteNote(id) {
            if (confirm('Are you sure you want to delete this note?')) {
                window.location = '?del=' + id;
            }
        }
    </script>
</body>
</html>