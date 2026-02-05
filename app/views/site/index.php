<?php

/** @var yii\web\View $this */
/** @var int $top */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Читайте наши книги!</h1>

        <p class="lead">В нашем каталоге уже более 709 книг от 200 авторов.</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>ТОП 10 авторов в <?= date('Y') ?> году</h2>

                <table class="table">
                    <tr>
                        <td>Автор</td>
                        <td>Выпущено книг</td>
                    </tr>
                    <?php foreach($top as $line): ?>
                        <tr>
                            <td><?= $line['name'] ?></td>
                            <td><?= $line['count_books'] ?>шт</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col-lg-6">
                <h2>Новости</h2>

                <p><span class="badge text-bg-secondary">12.12.2026</span>
                Вышла новая книга А.С. Довлатова "Над пропостью во ржи",
                читатели оценили ее по достоинству. Ознакомиться можно в
                нашем каталоге.</p>

                <p><span class="badge text-bg-secondary">12.12.2026</span>
                Вышла новая книга А.С. Довлатова "Над пропостью во ржи",
                читатели оценили ее по достоинству. Ознакомиться можно в
                нашем каталоге.</p>
            </div>
        </div>

    </div>
</div>
