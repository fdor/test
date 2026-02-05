<?php

return [
    'createBook' => [
        'type' => 2,
        'description' => 'Create a book',
    ],
    'updateBook' => [
        'type' => 2,
        'description' => 'Update a book',
    ],
    'deleteBook' => [
        'type' => 2,
        'description' => 'Delete a book',
    ],
    'createAuthor' => [
        'type' => 2,
        'description' => 'Create a author',
    ],
    'updateAuthor' => [
        'type' => 2,
        'description' => 'Update a author',
    ],
    'deleteAuthor' => [
        'type' => 2,
        'description' => 'Delete a author',
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'createBook',
            'updateBook',
            'deleteBook',
            'createAuthor',
            'updateAuthor',
            'deleteAuthor',
        ],
    ],
];
