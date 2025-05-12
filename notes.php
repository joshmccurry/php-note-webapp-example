<?php
// Simple Note - A simple note taking application
class Notes {

    private $pdo;

    const dbFile = 'db.sqlite';

    function __construct() {
        $this->pdo = new PDO('sqlite:'.self::dbFile);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS notes (
        ID      INTEGER PRIMARY KEY AUTOINCREMENT,
        title   TEXT NOT NULL,
        content TEXT NOT NULL,
        created DATETIME NOT NULL
        );');
    }

    public function fetchNotes($id = null) {
        if ($id != null) {
            $stmt = $this->pdo->prepare('SELECT title, content FROM notes WHERE id = :ID');
            $stmt->bindParam(':ID', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $title = $row['title'];
                header("Content-type: text/plain; charset=utf-8");
                header("Content-Disposition: attachment; filename=$title.txt");
                echo $row['content'];
                return;
            }
        } else {
            $stmt = $this->pdo->query('SELECT * FROM notes ORDER BY created DESC');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    public function create($title, $content) {
        $datetime = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare('INSERT INTO notes (title, content, created) VALUES (:title, :content, :created)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':created', $datetime);
        $stmt->execute();
    }

    public function delete($id) {
        if ($id == 'all') {
            $this->pdo->query('DELETE FROM notes; VACUUM');
        } else {
            $stmt = $this->pdo->prepare('DELETE FROM notes WHERE id = :ID');
            $stmt->bindParam(':ID', $id);
            $stmt->execute();
        }
    }

    public function edit($id, $title, $content) {
        $stmt = $this->pdo->prepare('UPDATE notes SET title = :title, content = :content WHERE id = :ID');
        $stmt->bindParam(':ID', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
    }
}

?>