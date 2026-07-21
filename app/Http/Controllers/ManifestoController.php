<?php

namespace App\Http\Controllers;

use App\Models\Manifesto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManifestoController extends Controller
{
    public function index(): View
    {
        $manifestoProcessado = null;

        if (session()->has('manifesto_processado')) {
            $manifestoProcessado = Manifesto::find(
                session('manifesto_processado')
            );
        }

        return $this->renderizarTela(
            null,
            $manifestoProcessado
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validarDados($request);

        $manifesto = Manifesto::create(
            $this->prepararDados($dados)
        );

        return redirect()
            ->route('manifestos.index')
            ->with(
                'success',
                'Manifesto cadastrado com sucesso.'
            )
            ->with(
                'manifesto_processado',
                $manifesto->id
            );
    }

    public function edit(Manifesto $manifesto): View
    {
        return $this->renderizarTela($manifesto);
    }

    public function update(
        Request $request,
        Manifesto $manifesto
    ): RedirectResponse {
        $dados = $this->validarDados(
            $request,
            $manifesto
        );

        $manifesto->update(
            $this->prepararDados($dados)
        );

        return redirect()
            ->route('manifestos.index')
            ->with(
                'success',
                'Manifesto atualizado com sucesso.'
            )
            ->with(
                'manifesto_processado',
                $manifesto->id
            );
    }

    private function renderizarTela(
        ?Manifesto $manifestoEmEdicao = null,
        ?Manifesto $manifestoProcessado = null
    ): View {
        $manifestos = Manifesto::latest()->get();

        $indicadores = [
            'total' => $manifestos->count(),
            'validados' => $manifestos->count(),
            'perigosos' => $manifestos
                ->where('hazmat', true)
                ->count(),
        ];

        return view(
            'welcome',
            compact(
                'manifestos',
                'indicadores',
                'manifestoEmEdicao',
                'manifestoProcessado'
            )
        );
    }

    private function validarDados(
        Request $request,
        ?Manifesto $manifesto = null
    ): array {
        $request->merge([
            'destination' => trim(
                (string) $request->input('destination')
            ),
        ]);

        $regraContainer = Rule::unique(
            'manifestos',
            'container_id'
        );

        if ($manifesto !== null) {
            $regraContainer->ignore($manifesto->id);
        }

        return $request->validate(
            [
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
                ],
                'weight' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'unit' => [
                    'required',
                    Rule::in(['kg', 'lb']),
                ],
                'hazmat' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'container_id.required' =>
                    'Informe o ID do contêiner.',
                'container_id.integer' =>
                    'O ID deve ser um número inteiro.',
                'container_id.min' =>
                    'O ID deve ser maior que zero.',
                'container_id.unique' =>
                    'Este contêiner já foi cadastrado.',
                'destination.required' =>
                    'Informe o destino da carga.',
                'destination.max' =>
                    'O destino deve ter no máximo 150 caracteres.',
                'weight.required' =>
                    'Informe o peso da carga.',
                'weight.numeric' =>
                    'O peso deve ser um número.',
                'weight.gt' =>
                    'O peso deve ser maior que zero.',
                'unit.required' =>
                    'Selecione a unidade de medida.',
                'unit.in' =>
                    'A unidade deve ser kg ou lb.',
                'hazmat.required' =>
                    'Informe a classificação da carga.',
                'hazmat.boolean' =>
                    'A classificação informada é inválida.',
            ]
        );
    }

    private function prepararDados(array $dados): array
    {
        $pesoOriginal = round(
            (float) $dados['weight'],
            2
        );

        $pesoEmQuilogramas =
            $dados['unit'] === 'lb'
                ? round($pesoOriginal * 0.45, 2)
                : $pesoOriginal;

        return [
            'container_id' => $dados['container_id'],
            'destination' => $dados['destination'],
            'original_weight' => $pesoOriginal,
            'original_unit' => $dados['unit'],
            'weight_kg' => $pesoEmQuilogramas,
            'hazmat' => (bool) $dados['hazmat'],
        ];
    }
}
public function destroy(Manifesto $manifesto): RedirectResponse
{
    $manifesto->delete();

    return redirect()
        ->route('manifestos.index')
        ->with('success', 'Manifesto excluído com sucesso.');
}