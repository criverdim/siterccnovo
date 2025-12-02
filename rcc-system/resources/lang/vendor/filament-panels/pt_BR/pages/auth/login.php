<?php

return [
    'title' => 'Entrar',
    'heading' => 'Entrar',
    'actions' => [
        'register' => [
            'before' => 'ou',
            'label' => 'crie uma conta',
        ],
        'request_password_reset' => [
            'label' => 'Esqueceu a senha?',
        ],
    ],
    'form' => [
        'email' => [ 'label' => 'E-mail' ],
        'password' => [ 'label' => 'Senha' ],
        'remember' => [ 'label' => 'Lembrar de mim' ],
        'actions' => [
            'authenticate' => [ 'label' => 'Entrar' ],
        ],
    ],
    'messages' => [
        'failed' => 'Credenciais inválidas.',
    ],
    'notifications' => [
        'throttled' => [
            'title' => 'Muitas tentativas de login',
            'body' => 'Tente novamente em :seconds segundos.',
        ],
    ],
];

