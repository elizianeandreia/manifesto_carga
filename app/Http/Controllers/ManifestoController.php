<?php

namespace App\Http\Controllers;

use App\Models\Manifesto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManifestoController extends Controller
{
    public function index(): View
    {
        $manifestos = Manifesto::query()
            ->latest()
            ->get();

        $indicadores = $this->calcularIndicadores($manifestos);

        $manifestoProcessado = $this->buscarManifestoProcessado();

        $manifestoEmEdicao = null;

        return view(
            'welcome',
            compact(
                'manifestos',
                'indicadores',
                'manifestoProcessado',
                'manifestoEmEdicao'
            )
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate(
            $this->regrasValidacao(),
            $this->mensagensValidacao(),
            $this->nomesDosCampos()
        );

        $manifesto = new Manifesto();

        $this->preencherManifesto($manifesto, $dados);

        $manifesto->save();

        return redirect()
            ->route('manifestos.index')
            ->with(
                'success',
                'Manifesto validado e cadastrado com sucesso.'
            )
            ->with(
                'manifesto_processado_id',
                $manifesto->getKey()
            );
    }

    public function edit(Manifesto $manifesto): View
    {
        $manifestos = Manifesto::query()
            ->latest()
            ->get();

        $indicadores = $this->calcularIndicadores($manifestos);

        $manifestoProcessado = null;

        $manifestoEmEdicao = $manifesto;

        return view(
            'welcome',
            compact(
                'manifestos',
                'indicadores',
                'manifestoProcessado',
                'manifestoEmEdicao'
            )
        );
    }

    public function update(
        Request $request,
        Manifesto $manifesto
    ): RedirectResponse {
        $dados = $request->validate(
            $this->regrasValidacao($manifesto),
            $this->mensagensValidacao(),
            $this->nomesDosCampos()
        );

        $this->preencherManifesto($manifesto, $dados);

        $manifesto->save();

        return redirect()
            ->route('manifestos.index')
            ->with(
                'success',
                'Manifesto atualizado com sucesso.'
            )
            ->with(
                'manifesto_processado_id',
                $manifesto->getKey()
            );
    }

    public function destroy(
        Manifesto $manifesto
    ): RedirectResponse {
        $manifesto->delete();

        return redirect()
            ->route('manifestos.index')
            ->with(
                'success',
                'Manifesto excluído com sucesso.'
            );
    }

    private function preencherManifesto(
        Manifesto $manifesto,
        array $dados
    ): void {
        $pesoOriginal = (float) $dados['weight'];
        $unidadeOriginal = strtolower($dados['unit']);

        $pesoEmQuilogramas = $this->normalizarPeso(
            $pesoOriginal,
            $unidadeOriginal
        );

        $manifesto->container_id = (int) $dados['container_id'];
        $manifesto->destination = trim($dados['destination']);
        $manifesto->original_weight = $pesoOriginal;
        $manifesto->original_unit = $unidadeOriginal;
        $manifesto->weight_kg = $pesoEmQuilogramas;
        $manifesto->hazmat = (bool) $dados['hazmat'];
    }

    private function normalizarPeso(
        float $peso,
        string $unidade
    ): float {
        if ($unidade === 'lb') {
            return round($peso * 0.45, 2);
        }

        return round($peso, 2);
    }

    private function regrasValidacao(
        ?Manifesto $manifesto = null
    ): array {
        $regraContainer = Rule::unique(
            'manifestos',
            'container_id'
        );

        if ($manifesto) {
            $regraContainer->ignore(
                $manifesto->getKey(),
                $manifesto->getKeyName()
            );
        }

        return [
            'container_id' => [
                'required',
                'integer',
                'min:1',
                $regraContainer,
            ],
            'destination' => [
                'required',
                'string',
                'max:150',
                function (
                    string $attribute,
                    mixed $value,
                    \Closure $fail
                ): void {
                    if (trim((string) $value) === '') {
                        $fail(
                            'O campo destino deve possuir um valor válido.'
                        );
                    }
                },
            ],
            'weight' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'unit' => [
                'required',
                Rule::in(['kg', 'lb']),
            ],
            'hazmat' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function mensagensValidacao(): array
    {
        return [
            'container_id.required' =>
                'Informe o ID do contêiner.',
            'container_id.integer' =>
                'O ID do contêiner deve ser um número inteiro.',
            'container_id.min' =>
                'O ID do contêiner deve ser maior que zero.',
            'container_id.unique' =>
                'Já existe um manifesto com esse ID de contêiner.',
            'destination.required' =>
                'Informe o destino da carga.',
            'destination.string' =>
                'O destino deve ser um texto válido.',
            'destination.max' =>
                'O destino deve possuir no máximo 150 caracteres.',
            'weight.required' =>
                'Informe o peso da carga.',
            'weight.numeric' =>
                'O peso deve ser um número válido.',
            'weight.min' =>
                'O peso deve ser maior que zero.',
            'unit.required' =>
                'Selecione a unidade de medida.',
            'unit.in' =>
                'A unidade de medida selecionada é inválida.',
            'hazmat.required' =>
                'Informe a classificação da carga.',
            'hazmat.boolean' =>
                'A classificação da carga é inválida.',
        ];
    }

    private function nomesDosCampos(): array
    {
        return [
            'container_id' => 'ID do contêiner',
            'destination' => 'destino',
            'weight' => 'peso',
            'unit' => 'unidade de medida',
            'hazmat' => 'classificação da carga',
        ];
    }

    private function calcularIndicadores(
        Collection $manifestos
    ): array {
        return [
            'total' => $manifestos->count(),
            'validados' => $manifestos->count(),
            'perigosos' => $manifestos
                ->where('hazmat', true)
                ->count(),
        ];
    }

    private function buscarManifestoProcessado(): ?Manifesto
    {
        $manifestoId = session(
            'manifesto_processado_id'
        );

        if (!$manifestoId) {
            return null;
        }

        return Manifesto::query()->find($manifestoId);
    }
}