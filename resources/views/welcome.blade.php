<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Módulo demonstrativo para gerenciamento de manifestos de carga."
    >

    <title>
        Módulo de Manifesto de Carga | LTHS Tecnologia
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .acoes-registro {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .acoes-registro form {
            display: inline-flex;
            margin: 0;
        }

        .botao-excluir {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-height: 26px;
            padding: 4px 9px;
            color: #8b1d1d;
            background: #fff0f0;
            border: 1px solid #dba0a0;
            border-radius: 2px;
            font: inherit;
            font-size: 12px;
            line-height: 1;
            cursor: pointer;
        }

        .botao-excluir:hover {
            color: #ffffff;
            background: #b83232;
            border-color: #922626;
        }
    </style>
</head>

<body>
    <div class="sistema">
        <header class="barra-superior">
            <div class="marca-sistema">
                <img
                    src="{{ asset('imagens/logo2026.webp') }}"
                    alt="Logo da LTHS Tecnologia"
                >

                <div>
                    <strong>LTHS Tecnologia</strong>
                    <span>Sistema de Gestão Logística</span>
                </div>
            </div>

            <h1>Módulo de Manifesto de Carga</h1>

            <div class="usuario-sistema">
                <i class="bi bi-person-circle"></i>

                <div>
                    <strong>Operador</strong>
                    <span>Sistema local</span>
                </div>
            </div>
        </header>

        <nav class="barra-ferramentas">
            <a
                href="{{ route('manifestos.index') }}"
                class="ferramenta"
            >
                <i class="bi bi-file-earmark-plus"></i>
                <span>Novo</span>
            </a>

            <button
                type="submit"
                form="form-manifesto"
                class="ferramenta"
            >
                <i class="bi bi-floppy"></i>

                <span>
                    {{ $manifestoEmEdicao
                        ? 'Atualizar'
                        : 'Salvar' }}
                </span>
            </button>

            <a href="#historico" class="ferramenta">
                <i class="bi bi-search"></i>
                <span>Pesquisar</span>
            </a>

            <button
                type="button"
                class="ferramenta"
                onclick="window.print()"
            >
                <i class="bi bi-printer"></i>
                <span>Imprimir</span>
            </button>

            <button
                type="button"
                class="ferramenta"
                data-bs-toggle="modal"
                data-bs-target="#modalAjuda"
            >
                <i class="bi bi-question-circle"></i>
                <span>Ajuda</span>
            </button>
        </nav>

        <main class="area-trabalho">
            <section class="cabecalho-registro">
                <div class="numero-carga">
                    <span>Nº DA CARGA:</span>

                    <strong>
                        @if ($manifestoEmEdicao)
                            {{ $manifestoEmEdicao->container_id }}
                        @elseif ($manifestoProcessado)
                            {{ $manifestoProcessado->container_id }}
                        @else
                            NOVO
                        @endif
                    </strong>
                </div>

                <div class="situacao-registro">
                    <span>Situação:</span>

                    @if ($manifestoEmEdicao)
                        <strong class="situacao edicao">
                            <i class="bi bi-pencil-square"></i>
                            EM ALTERAÇÃO
                        </strong>
                    @elseif ($manifestoProcessado)
                        <strong class="situacao cadastrado">
                            <i class="bi bi-check-square-fill"></i>
                            CADASTRADO
                        </strong>
                    @elseif ($errors->any())
                        <strong class="situacao erro">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            ERRO DE VALIDAÇÃO
                        </strong>
                    @else
                        <strong class="situacao digitacao">
                            <i class="bi bi-pencil-square"></i>
                            EM DIGITAÇÃO
                        </strong>
                    @endif
                </div>

                <div class="data-registro">
                    <span>Data e hora:</span>

                    <strong>
                        {{ now('America/Sao_Paulo')
                            ->format('d/m/Y H:i') }}
                    </strong>
                </div>
            </section>

            @if (session('success'))
                <div class="mensagem-sistema sucesso">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mensagem-sistema falha">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Existem informações ausentes ou inválidas.
                    Verifique os campos destacados.
                </div>
            @endif

            <form
                id="form-manifesto"
                method="POST"
                action="{{ $manifestoEmEdicao
                    ? route(
                        'manifestos.update',
                        $manifestoEmEdicao
                    )
                    : route('manifestos.store') }}"
                autocomplete="off"
            >
                @csrf

                @if ($manifestoEmEdicao)
                    @method('PUT')
                @endif

                <div class="grade-operacional">
                    <div class="coluna-formulario">
                        <fieldset class="grupo-erp">
                            <legend>Informações da carga</legend>

                            <div class="linha-formulario">
                                <div class="campo campo-id">
                                    <label for="container_id">
                                        ID do contêiner
                                    </label>

                                    <div class="controle-com-icone">
                                        <input
                                            type="number"
                                            id="container_id"
                                            name="container_id"
                                            min="1"
                                            step="1"
                                            value="{{ old(
                                                'container_id',
                                                $manifestoEmEdicao
                                                    ?->container_id
                                            ) }}"
                                            class="@error('container_id')
                                                invalido
                                            @enderror"
                                        >

                                        <i class="bi bi-upc-scan"></i>
                                    </div>

                                    @error('container_id')
                                        <small class="erro-campo">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="campo campo-destino">
                                    <label for="destination">
                                        Destino
                                    </label>

                                    <div class="controle-com-icone">
                                        <input
                                            type="text"
                                            id="destination"
                                            name="destination"
                                            maxlength="150"
                                            value="{{ old(
                                                'destination',
                                                $manifestoEmEdicao
                                                    ?->destination
                                            ) }}"
                                            class="@error('destination')
                                                invalido
                                            @enderror"
                                        >

                                        <i class="bi bi-geo-alt"></i>
                                    </div>

                                    @error('destination')
                                        <small class="erro-campo">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="grupo-erp">
                            <legend>Peso e classificação</legend>

                            <div class="linha-formulario">
                                <div class="campo">
                                    <label for="weight">
                                        Peso da carga
                                    </label>

                                    <div class="controle-com-icone">
                                        <input
                                            type="number"
                                            id="weight"
                                            name="weight"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ old(
                                                'weight',
                                                $manifestoEmEdicao
                                                    ?->original_weight
                                            ) }}"
                                            class="@error('weight')
                                                invalido
                                            @enderror"
                                        >

                                        <i class="bi bi-speedometer2"></i>
                                    </div>

                                    @error('weight')
                                        <small class="erro-campo">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="campo">
                                    <label for="unit">
                                        Unidade de medida
                                    </label>

                                    <select
                                        id="unit"
                                        name="unit"
                                        class="@error('unit')
                                            invalido
                                        @enderror"
                                    >
                                        <option value="">
                                            Selecione
                                        </option>

                                        <option
                                            value="kg"
                                            @selected(
                                                old(
                                                    'unit',
                                                    $manifestoEmEdicao
                                                        ?->original_unit
                                                ) === 'kg'
                                            )
                                        >
                                            KG - Quilogramas
                                        </option>

                                        <option
                                            value="lb"
                                            @selected(
                                                old(
                                                    'unit',
                                                    $manifestoEmEdicao
                                                        ?->original_unit
                                                ) === 'lb'
                                            )
                                        >
                                            LB - Libras
                                        </option>
                                    </select>

                                    @error('unit')
                                        <small class="erro-campo">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="linha-classificacao">
                                <span class="rotulo-classificacao">
                                    Classificação da carga:
                                </span>

                                <label class="opcao-radio">
                                    <input
                                        type="radio"
                                        name="hazmat"
                                        value="0"
                                        @checked(
                                            old(
                                                'hazmat',
                                                $manifestoEmEdicao
                                                    ? (
                                                        $manifestoEmEdicao
                                                            ->hazmat
                                                            ? '1'
                                                            : '0'
                                                    )
                                                    : null
                                            ) === '0'
                                        )
                                    >

                                    <span>
                                        <i class="bi bi-shield-check"></i>
                                        Carga comum
                                    </span>
                                </label>

                                <label class="opcao-radio perigosa">
                                    <input
                                        type="radio"
                                        name="hazmat"
                                        value="1"
                                        @checked(
                                            old(
                                                'hazmat',
                                                $manifestoEmEdicao
                                                    ? (
                                                        $manifestoEmEdicao
                                                            ->hazmat
                                                            ? '1'
                                                            : '0'
                                                    )
                                                    : null
                                            ) === '1'
                                        )
                                    >

                                    <span>
                                        <i class="bi bi-exclamation-diamond"></i>
                                        Carga perigosa
                                    </span>
                                </label>
                            </div>

                            @error('hazmat')
                                <small class="erro-campo">
                                    {{ $message }}
                                </small>
                            @enderror
                        </fieldset>

                        <fieldset class="grupo-erp">
                            <legend>Parâmetros do manifesto</legend>

                            <div class="informacoes-processamento">
                                <div>
                                    <span>Unidade padrão</span>
                                    <strong>Quilogramas (kg)</strong>
                                </div>

                                <div>
                                    <span>Conversão de peso</span>
                                    <strong>Automática</strong>
                                </div>

                                <div>
                                    <span>Classificação</span>
                                    <strong>Comum ou perigosa</strong>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <aside class="painel-resultado">
                        <div class="titulo-painel">
                            <i class="bi bi-clipboard-check"></i>
                            Resultado do processamento
                        </div>

                        @if ($manifestoEmEdicao)
                            <div class="resultado-validado">
                                <div class="selo-validado">
                                    <i class="bi bi-pencil-square"></i>
                                    Registro carregado para alteração
                                </div>

                                <dl>
                                    <div>
                                        <dt>Contêiner atual</dt>

                                        <dd>
                                            #{{ $manifestoEmEdicao
                                                ->container_id }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Destino atual</dt>

                                        <dd>
                                            {{ $manifestoEmEdicao
                                                ->destination }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Peso atual</dt>

                                        <dd>
                                            {{ number_format(
                                                (float)
                                                $manifestoEmEdicao
                                                    ->original_weight,
                                                2,
                                                ',',
                                                '.'
                                            ) }}

                                            {{ strtoupper(
                                                $manifestoEmEdicao
                                                    ->original_unit
                                            ) }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Classificação</dt>

                                        <dd>
                                            @if ($manifestoEmEdicao->hazmat)
                                                <span class="texto-perigo">
                                                    Carga perigosa
                                                </span>
                                            @else
                                                <span class="texto-sucesso">
                                                    Carga comum
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                <div class="peso-processado">
                                    <span>
                                        PESO NORMALIZADO ATUAL
                                    </span>

                                    <strong>
                                        {{ number_format(
                                            (float)
                                            $manifestoEmEdicao
                                                ->weight_kg,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                        kg
                                    </strong>
                                </div>
                            </div>
                        @elseif ($manifestoProcessado)
                            <div class="resultado-validado">
                                <div class="selo-validado">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Manifesto válido
                                </div>

                                <dl>
                                    <div>
                                        <dt>Contêiner</dt>

                                        <dd>
                                            #{{ $manifestoProcessado
                                                ->container_id }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Destino</dt>

                                        <dd>
                                            {{ $manifestoProcessado
                                                ->destination }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Peso informado</dt>

                                        <dd>
                                            {{ number_format(
                                                (float)
                                                $manifestoProcessado
                                                    ->original_weight,
                                                2,
                                                ',',
                                                '.'
                                            ) }}

                                            {{ strtoupper(
                                                $manifestoProcessado
                                                    ->original_unit
                                            ) }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Classificação</dt>

                                        <dd>
                                            @if ($manifestoProcessado->hazmat)
                                                <span class="texto-perigo">
                                                    Carga perigosa
                                                </span>
                                            @else
                                                <span class="texto-sucesso">
                                                    Carga comum
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                <div class="peso-processado">
                                    <span>PESO NORMALIZADO</span>

                                    <strong>
                                        {{ number_format(
                                            (float)
                                            $manifestoProcessado
                                                ->weight_kg,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                        kg
                                    </strong>
                                </div>
                            </div>
                        @elseif ($errors->any())
                            <div class="resultado-invalido">
                                <i class="bi bi-x-octagon-fill"></i>

                                <strong>Manifesto inválido</strong>

                                <span>
                                    Corrija os campos indicados para
                                    continuar.
                                </span>

                                <ul>
                                    @foreach ($errors->all() as $erro)
                                        <li>{{ $erro }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="resultado-aguardando">
                                <i class="bi bi-hourglass-split"></i>

                                <strong>
                                    Aguardando processamento
                                </strong>

                                <span>
                                    Preencha os dados e clique em Salvar.
                                </span>
                            </div>
                        @endif
                    </aside>
                </div>

                <div class="acoes-formulario">
                    <button
                        type="submit"
                        class="botao-erp salvar"
                    >
                        <i class="bi bi-check-lg"></i>

                        {{ $manifestoEmEdicao
                            ? 'Salvar alterações'
                            : 'Salvar e processar' }}
                    </button>

                    <a
                        href="{{ route('manifestos.index') }}"
                        class="botao-erp cancelar"
                    >
                        <i class="bi bi-x-lg"></i>

                        {{ $manifestoEmEdicao
                            ? 'Cancelar edição'
                            : 'Cancelar' }}
                    </a>
                </div>
            </form>

            <section id="historico" class="historico-erp">
                <div class="titulo-painel">
                    <i class="bi bi-table"></i>
                    Histórico de manifestos

                    <span>
                        {{ $manifestos->count() }} registro(s)
                    </span>
                </div>

                <div class="tabela-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nº da carga</th>
                                <th>Destino</th>
                                <th>Peso informado</th>
                                <th>Peso normalizado</th>
                                <th>Classificação</th>
                                <th>Cadastro</th>
                                <th>Última alteração</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($manifestos as $manifesto)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $manifesto->container_id }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $manifesto->destination }}
                                    </td>

                                    <td>
                                        {{ number_format(
                                            (float)
                                            $manifesto->original_weight,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                        {{ strtoupper(
                                            $manifesto->original_unit
                                        ) }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format(
                                                (float)
                                                $manifesto->weight_kg,
                                                2,
                                                ',',
                                                '.'
                                            ) }}
                                            kg
                                        </strong>
                                    </td>

                                    <td>
                                        @if ($manifesto->hazmat)
                                            <span
                                                class="status-tabela perigosa"
                                            >
                                                Perigosa
                                            </span>
                                        @else
                                            <span
                                                class="status-tabela comum"
                                            >
                                                Comum
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $manifesto->created_at
                                            ->timezone(
                                                'America/Sao_Paulo'
                                            )
                                            ->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        @if (
                                            $manifesto->updated_at
                                                ->gt(
                                                    $manifesto
                                                        ->created_at
                                                )
                                        )
                                            {{ $manifesto->updated_at
                                                ->timezone(
                                                    'America/Sao_Paulo'
                                                )
                                                ->format(
                                                    'd/m/Y H:i'
                                                ) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        <div class="acoes-registro">
                                            <a
                                                href="{{ route(
                                                    'manifestos.edit',
                                                    $manifesto
                                                ) }}"
                                                class="botao-editar"
                                                title="Editar manifesto"
                                            >
                                                <i
                                                    class="bi bi-pencil-square"
                                                ></i>
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route(
                                                    'manifestos.destroy',
                                                    $manifesto
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm('Deseja realmente excluir o manifesto nº {{ $manifesto->container_id }}?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="botao-excluir"
                                                    title="Excluir manifesto"
                                                    aria-label="Excluir manifesto {{ $manifesto->container_id }}"
                                                >
                                                    <i
                                                        class="bi bi-trash3"
                                                    ></i>
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="8"
                                        class="sem-registros"
                                    >
                                        Nenhum manifesto cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="totais-operacionais">
                    <div>
                        <span>Total de cargas</span>
                        <strong>{{ $indicadores['total'] }}</strong>
                    </div>

                    <div>
                        <span>Cargas validadas</span>

                        <strong>
                            {{ $indicadores['validados'] }}
                        </strong>
                    </div>

                    <div>
                        <span>Cargas perigosas</span>

                        <strong>
                            {{ $indicadores['perigosos'] }}
                        </strong>
                    </div>

                    <div>
                        <span>Peso total normalizado</span>

                        <strong>
                            {{ number_format(
                                (float)
                                $manifestos->sum('weight_kg'),
                                2,
                                ',',
                                '.'
                            ) }}
                            kg
                        </strong>
                    </div>
                </div>
            </section>
        </main>

        <footer class="barra-status">
            <span>
                <i class="bi bi-circle-fill"></i>
                Sistema disponível
            </span>

            <span>
                Módulo: Manifesto de Carga
            </span>

            <span>
                Logística
            </span>

            <span class="direitos">
                &copy; {{ date('Y') }} LTHS Tecnologia.
                Todos os direitos reservados.
            </span>
        </footer>
    </div>

    <div
        class="modal fade"
        id="modalAjuda"
        tabindex="-1"
        aria-labelledby="tituloModalAjuda"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2
                        class="modal-title fs-5"
                        id="tituloModalAjuda"
                    >
                        Ajuda do sistema
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fechar"
                    ></button>
                </div>

                <div class="modal-body">
                    <p>
                        Preencha os dados do contêiner, informe o
                        peso, selecione a unidade e indique a
                        classificação da carga.
                    </p>

                    <p>
                        Ao salvar, o sistema validará os dados,
                        converterá libras para quilogramas e
                        armazenará o manifesto.
                    </p>

                    <p>
                        Para corrigir um registro, clique em Editar
                        no histórico, altere os dados e selecione
                        Salvar alterações.
                    </p>

                    <p class="mb-0">
                        Para remover um registro, clique em Excluir
                        e confirme a operação.
                    </p>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal"
                    >
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>