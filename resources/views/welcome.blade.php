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
        content="Sistema de validação e gerenciamento de manifestos de carga."
    >

    <title>Manifesto de Carga | LTHS Tecnologia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container py-2">
            <a
                class="navbar-brand d-flex align-items-center gap-3"
                href="{{ route('manifestos.index') }}"
            >
                <span class="logo-container">
                    <img
                        src="{{ asset('imagens/logo2026.webp') }}"
                        alt="Logo da LTHS Tecnologia"
                        class="logo-empresa"
                    >
                </span>

                <span>
                    <strong>LTHS Tecnologia</strong>

                    <small class="d-block text-white-50">
                        CargaSegura • Gestão logística
                    </small>
                </span>
            </a>

            <div class="d-flex align-items-center gap-2 text-white-50">
                <span class="status-online"></span>
                <small>Sistema operacional</small>
            </div>
        </div>
    </nav>

    <header class="cabecalho-pagina">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="etiqueta">
                        PAINEL DE CONTROLE
                    </span>

                    <h1 class="mt-3 mb-2">
                        Manifesto de Carga
                    </h1>

                    <p class="mb-0">
                        Cadastre, valide e normalize as informações das
                        cargas transportadas.
                    </p>
                </div>

                <div class="col-lg-4">
                    <div class="data-atual">
                        <i class="bi bi-calendar3"></i>

                        <div>
                            <small>Data de operação</small>

                            <strong>
                                {{ now()->format('d/m/Y') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container py-4 py-lg-5">
        @if (session('success'))
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>

                    <span>{{ session('success') }}</span>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Fechar"
                ></button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <strong>
                        Não foi possível processar o manifesto.
                    </strong>
                </div>

                <span class="d-block mt-1">
                    Verifique os campos destacados e tente novamente.
                </span>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Fechar"
                ></button>
            </div>
        @endif

        <section class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card cartao-indicador h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="indicador-icone azul">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>

                        <div>
                            <small>Manifestos cadastrados</small>

                            <h2 class="mb-0">
                                {{ $indicadores['total'] }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card cartao-indicador h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="indicador-icone verde">
                            <i class="bi bi-check-circle"></i>
                        </span>

                        <div>
                            <small>Cargas validadas</small>

                            <h2 class="mb-0">
                                {{ $indicadores['validados'] }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card cartao-indicador h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="indicador-icone amarelo">
                            <i class="bi bi-exclamation-triangle"></i>
                        </span>

                        <div>
                            <small>Cargas perigosas</small>

                            <h2 class="mb-0">
                                {{ $indicadores['perigosos'] }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-lg-8">
                <section class="card cartao-principal">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <span class="numero-etapa">01</span>

                            <div>
                                <h2 class="h5 mb-1">
                                    Dados do manifesto
                                </h2>

                                <p class="text-secondary small mb-0">
                                    Preencha todos os campos obrigatórios.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form
                            method="POST"
                            action="{{ route('manifestos.store') }}"
                        >
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label
                                        for="container_id"
                                        class="form-label"
                                    >
                                        ID do contêiner
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-upc-scan"></i>
                                        </span>

                                        <input
                                            type="number"
                                            id="container_id"
                                            name="container_id"
                                            min="1"
                                            step="1"
                                            value="{{ old('container_id') }}"
                                            placeholder="Ex.: 55"
                                            class="form-control
                                                @error('container_id')
                                                    is-invalid
                                                @enderror"
                                        >

                                        @error('container_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label
                                        for="destination"
                                        class="form-label"
                                    >
                                        Destino
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-geo-alt"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="destination"
                                            name="destination"
                                            maxlength="150"
                                            value="{{ old('destination') }}"
                                            placeholder="Ex.: São Paulo"
                                            class="form-control
                                                @error('destination')
                                                    is-invalid
                                                @enderror"
                                        >

                                        @error('destination')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label
                                        for="weight"
                                        class="form-label"
                                    >
                                        Peso da carga
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-speedometer2"></i>
                                        </span>

                                        <input
                                            type="number"
                                            id="weight"
                                            name="weight"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ old('weight') }}"
                                            placeholder="Ex.: 400"
                                            class="form-control
                                                @error('weight')
                                                    is-invalid
                                                @enderror"
                                        >

                                        @error('weight')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label
                                        for="unit"
                                        class="form-label"
                                    >
                                        Unidade de medida
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-rulers"></i>
                                        </span>

                                        <select
                                            id="unit"
                                            name="unit"
                                            class="form-select
                                                @error('unit')
                                                    is-invalid
                                                @enderror"
                                        >
                                            <option value="">
                                                Selecione
                                            </option>

                                            <option
                                                value="kg"
                                                @selected(old('unit') === 'kg')
                                            >
                                                Quilogramas (kg)
                                            </option>

                                            <option
                                                value="lb"
                                                @selected(old('unit') === 'lb')
                                            >
                                                Libras (lb)
                                            </option>
                                        </select>

                                        @error('unit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <fieldset class="area-perigosa">
                                        <legend>
                                            A carga é perigosa?
                                        </legend>

                                        <p>
                                            Informe se o transporte exige
                                            cuidados especiais.
                                        </p>

                                        <div class="d-flex flex-wrap gap-3">
                                            <label class="opcao-carga">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="hazmat"
                                                    value="1"
                                                    @checked(
                                                        old('hazmat') === '1'
                                                    )
                                                >

                                                <span>
                                                    <i class="bi bi-exclamation-diamond"></i>
                                                    Sim
                                                </span>
                                            </label>

                                            <label class="opcao-carga">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="hazmat"
                                                    value="0"
                                                    @checked(
                                                        old('hazmat') === '0'
                                                    )
                                                >

                                                <span>
                                                    <i class="bi bi-shield-check"></i>
                                                    Não
                                                </span>
                                            </label>
                                        </div>

                                        @error('hazmat')
                                            <div
                                                class="text-danger small mt-2"
                                            >
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>

                            <div
                                class="d-flex flex-column flex-sm-row
                                    justify-content-end gap-2 mt-4"
                            >
                                <button
                                    type="reset"
                                    class="btn btn-light px-4"
                                >
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                                    Limpar
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4"
                                >
                                    <i class="bi bi-check2-circle me-2"></i>
                                    Validar e processar
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <div class="col-lg-4">
                <section class="card cartao-principal resultado">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <span class="numero-etapa">02</span>

                            <div>
                                <h2 class="h5 mb-1">
                                    Resultado
                                </h2>

                                <p class="text-secondary small mb-0">
                                    Resumo do processamento
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($manifestoProcessado)
                            <div class="resultado-sucesso">
                                <span class="badge text-bg-success mb-3">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Manifesto validado
                                </span>

                                <div class="detalhe-resultado">
                                    <span>ID do contêiner</span>

                                    <strong>
                                        #{{ $manifestoProcessado->container_id }}
                                    </strong>
                                </div>

                                <div class="detalhe-resultado">
                                    <span>Destino</span>

                                    <strong>
                                        {{ $manifestoProcessado->destination }}
                                    </strong>
                                </div>

                                <div class="detalhe-resultado">
                                    <span>Peso informado</span>

                                    <strong>
                                        {{ number_format(
                                            (float) $manifestoProcessado->original_weight,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                        {{ $manifestoProcessado->original_unit }}
                                    </strong>
                                </div>

                                <div class="detalhe-resultado">
                                    <span>Tipo de carga</span>

                                    @if ($manifestoProcessado->hazmat)
                                        <strong class="text-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Perigosa
                                        </strong>
                                    @else
                                        <strong class="text-success">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Comum
                                        </strong>
                                    @endif
                                </div>

                                <div class="peso-normalizado">
                                    <span>Peso normalizado</span>

                                    <strong>
                                        {{ number_format(
                                            (float) $manifestoProcessado->weight_kg,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                        kg
                                    </strong>

                                    @if (
                                        $manifestoProcessado->original_unit
                                        === 'lb'
                                    )
                                        <small>
                                            Conversão aplicada:
                                            1 lb = 0,45 kg
                                        </small>
                                    @else
                                        <small>
                                            O peso já foi informado em
                                            quilogramas.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @elseif ($errors->any())
                            <div class="resultado-com-erro">
                                <span>
                                    <i class="bi bi-x-circle"></i>
                                </span>

                                <h3>Manifesto inválido</h3>

                                <p>
                                    Corrija os campos destacados antes de
                                    realizar o processamento.
                                </p>

                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="resultado-vazio">
                                <span>
                                    <i class="bi bi-clipboard-data"></i>
                                </span>

                                <h3>
                                    Nenhum manifesto processado
                                </h3>

                                <p>
                                    Preencha o formulário para validar e
                                    normalizar os dados da carga.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>

        @if ($manifestos->isNotEmpty())
            <section class="card cartao-principal mt-4">
                <div class="card-header bg-white">
                    <div
                        class="d-flex flex-column flex-sm-row
                            align-items-sm-center
                            justify-content-between gap-3"
                    >
                        <div class="d-flex align-items-center gap-3">
                            <span class="numero-etapa">03</span>

                            <div>
                                <h2 class="h5 mb-1">
                                    Histórico de manifestos
                                </h2>

                                <p class="text-secondary small mb-0">
                                    Registros processados pelo sistema
                                </p>
                            </div>
                        </div>

                        <span class="badge text-bg-light">
                            {{ $manifestos->count() }}
                            registro(s)
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table tabela-manifestos align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Contêiner</th>
                                <th>Destino</th>
                                <th>Peso informado</th>
                                <th>Peso normalizado</th>
                                <th>Classificação</th>
                                <th>Cadastro</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($manifestos as $manifesto)
                                <tr>
                                    <td>
                                        <strong>
                                            #{{ $manifesto->container_id }}
                                        </strong>
                                    </td>

                                    <td>
                                        <i class="bi bi-geo-alt text-primary me-1"></i>
                                        {{ $manifesto->destination }}
                                    </td>

                                    <td>
                                        {{ number_format(
                                            (float) $manifesto->original_weight,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                        {{ $manifesto->original_unit }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format(
                                                (float) $manifesto->weight_kg,
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
                                                class="badge
                                                    text-bg-danger"
                                            >
                                                Perigosa
                                            </span>
                                        @else
                                            <span
                                                class="badge
                                                    text-bg-success"
                                            >
                                                Comum
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $manifesto->created_at
                                            ->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>

    <footer class="rodape">
        <div
            class="container d-flex flex-column flex-md-row
                align-items-center justify-content-between gap-3"
        >
            <div class="empresa-rodape">
                <img
                    src="{{ asset('imagens/logo2026.webp') }}"
                    alt="Logo da LTHS Tecnologia"
                    class="logo-rodape"
                >

                <div>
                    <strong>LTHS Tecnologia</strong>

                    <small>
                        CargaSegura • Sistema de validação logística
                    </small>
                </div>
            </div>

            <span class="direitos-autorais">
                &copy; {{ date('Y') }} LTHS Tecnologia.
                Todos os direitos reservados.
            </span>
        </div>
    </footer>
</body>
</html>