<?php

namespace App\Http\Controllers;

use App\Models\Manifesto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManifestoController extends Controller
{
 public function index(): View
{
    $manifestos = Manifesto::latest()->get();

    $indicadores = [
        'total' => $manifestos->count(),
        'validados' => $manifestos->count(),
        'perigosos' => $manifestos->where('hazmat', true)->count(),
    ];

    $manifestoProcessado = null;

    if (session()->has('manifesto_processado')) {
        $manifestoProcessado = Manifesto::find(
            session('manifesto_processado')
        );
    }

    return view(
        'welcome',
        compact('manifestos', 'indicadores', 'manifestoProcessado')
    );
}

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate(
            [
                'container_id' => [
                    'required',
                    'integer',
                    'min:1',
                    'unique:manifestos,container_id',
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
                    'in:kg,lb',
                ],
                'hazmat' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'container_id.required' => 'Informe o ID do contêiner.',
                'container_id.integer' => 'O ID deve ser um número inteiro.',
                'container_id.min' => 'O ID deve ser maior que zero.',
                'container_id.unique' => 'Este contêiner já foi cadastrado.',

                'destination.required' => 'Informe o destino da carga.',
                'destination.string' => 'O destino informado é inválido.',
                'destination.max' => 'O destino deve ter no máximo 150 caracteres.',

                'weight.required' => 'Informe o peso da carga.',
                'weight.numeric' => 'O peso deve ser um número.',
                'weight.gt' => 'O peso deve ser maior que zero.',

                'unit.required' => 'Selecione a unidade de medida.',
                'unit.in' => 'A unidade deve ser kg ou lb.',

                'hazmat.required' => 'Informe se a carga é perigosa.',
                'hazmat.boolean' => 'A informação sobre carga perigosa é inválida.',
            ]
        );

        $pesoEmQuilogramas = $dados['unit'] === 'lb'
            ? round($dados['weight'] * 0.45, 2)
            : round($dados['weight'], 2);

        $manifesto = Manifesto::create([
            'container_id' => $dados['container_id'],
            'destination' => trim($dados['destination']),
            'original_weight' => $dados['weight'],
            'original_unit' => $dados['unit'],
            'weight_kg' => $pesoEmQuilogramas,
            'hazmat' => $dados['hazmat'],
        ]);

        return redirect()
            ->route('manifestos.index')
            ->with('success', 'Manifesto validado e cadastrado com sucesso.')
            ->with('manifesto_processado', $manifesto->id);
    }
}