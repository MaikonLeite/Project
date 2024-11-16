<?php include('../layouts/header.php'); ?>

<div class="container mt-5">
    <h1>Você é do signo de:</h1>
    <?php
    if (isset($_POST['data_nascimento'])) {
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $data_nascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento);

        if ($data_nascimento === false) {
            echo "<p>Data de nascimento inválida. Use o formato dd/mm/yyyy.</p>";
        } else {
            $signos = simplexml_load_file("signos.xml");

            if ($signos === false) {
                echo "<p>Erro ao carregar o arquivo de signos.</p>";
            } else {
                $signo_encontrado = false;

                foreach ($signos->signo as $signo) {
                    $data_inicio = DateTime::createFromFormat('d/m', (string)$signo->dataInicio);
                    $data_fim = DateTime::createFromFormat('d/m', (string)$signo->dataFim);

                    // Definir o ano da data de início e fim
                    $data_inicio->setDate($data_nascimento->format('Y'), $data_inicio->format('m'), $data_inicio->format('d'));
                    $data_fim->setDate($data_nascimento->format('Y'), $data_fim->format('m'), $data_fim->format('d'));

                    // Ajustar o fim se a data de início for depois do fim
                    if ($data_inicio > $data_fim) {
                        $data_fim->modify('+1 year');
                    }

                    // Verifica se a data de nascimento está dentro do intervalo
                    if ($data_nascimento >= $data_inicio && $data_nascimento <= $data_fim) {
                        echo "<h2>{$signo->signoNome}</h2>";
                        echo "<p>{$signo->descricao}</p>";
                        $signo_encontrado = true;
                        break;
                    }
                }

                if (!$signo_encontrado) {
                    echo "<p>Não foi possível determinar seu signo. Verifique a data informada.</p>";
                }
            }
        }
    } else {
        echo "<p>Por favor, informe sua data de nascimento.</p>";
    }
    ?>
    <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>


