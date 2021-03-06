<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = ['periciasPersona', 'feitosPersona', 'desvantagensPersona', 'poderesPersona', 'poderPersona']; 

    protected $appends = ['pericias', 'feitos', 'desvantagens', 'poderes'];

    public function getPericiasAttribute() 
    {
        return $this->periciasPersona->map(function ($pericia) {
            $id = $pericia->id;
            $nome = $pericia->nome;
            $habilidade_chave = $pericia->habilidade_chave;
            $graduacao = $pericia->info->graduacao;
            return compact(['id', 'nome', 'habilidade_chave', 'graduacao']);
        }); 
    }

    public function getFeitosAttribute() 
    {
        return $this->feitosPersona->map(function ($feito) {
            $id = $feito->id;
            $nome = $feito->nome;
            $graduacao = $feito->info->graduacao;
            return compact(['id', 'nome', 'graduacao']);
        }); 
    }

    public function getDesvantagensAttribute() 
    {
        return $this->desvantagensPersona->map(function ($desvantagem) {
            $id = $desvantagem->id;
            $nome = $desvantagem->nome;
            $graduacao = $desvantagem->info->graduacao;
            return compact(['id', 'nome', 'graduacao']);
        }); 
    }

    public function getPoderesAttribute() 
    {
        return $this->poderPersona;
    }

    public function periciasSync($pericias) 
    {
        $pericias_persona = array();
        foreach ($pericias as $pericia) {
            $pericias_persona[$pericia['id']] = ['graduacao' => $pericia['graduacao']];
        }
        $this->periciasPersona()->sync($pericias_persona);
    }

    public function feitosSync($feitos) 
    {
        $feitos_persona = array();
        foreach ($feitos as $feito) {
            $feitos_persona[$feito['id']] = ['graduacao' => $feito['graduacao']];
        }
        $this->feitosPersona()->sync($feitos_persona);
    }

    public function desvantagensSync($desvantagens) 
    {
        $desvantagens_persona = array();
        foreach ($desvantagens as $desvantagem) {
            $desvantagens_persona[$desvantagem['id']] = ['graduacao' => $desvantagem['graduacao']];
        }
        $this->desvantagensPersona()->sync($desvantagens_persona);
    }

    public function poderesSync($poderes) 
    {
        $this->poderesPersona()->detach();

        foreach ($poderes as $poder) {
            $poderPersona = factory(PoderPersona::class)->create([
                'persona_id' => $this->id,
                'poder_id' => $poder['poder_id'],
                'graduacao' => $poder['graduacao'],
                'custo' => $poder['custo'],
            ]);

            $extras_persona = array();
            foreach ($poder['extras'] as $extra) {
                $extras_persona[$extra['id']] = ['modificador' => $extra['modificador']];
            }
            $poderPersona->extrasPersona()->attach($extras_persona);

            $falhas_persona = array();
            foreach ($poder['falhas'] as $falha) {
                $falhas_persona[$falha['id']] = ['modificador' => $falha['modificador']];
            }
            $poderPersona->falhasPersona()->attach($falhas_persona);

            $opcoes_persona = array();
            foreach ($poder['opcoes'] as $opcao) {
                $opcoes_persona[$opcao['id']] = ['modificador' => $opcao['modificador']];
            }
            $poderPersona->opcoesPersona()->attach($opcoes_persona);

            $poderes_alternativos = array();
            foreach ($poder['poderes_alternativos'] as $poder_alternativo) {
                $poderes_alternativos[$poder_alternativo['id']] = ['modificador' => $poder_alternativo['modificador']];
            }
            $poderPersona->poderesAlternativosPersona()->attach($poderes_alternativos);
        }
    }

    public function periciasPersona()
    {
        return $this->belongsToMany('App\Pericia', 'pericia_persona')
        ->as('info')
        ->withPivot('graduacao');
    }

    public function feitosPersona()
    {
        return $this->belongsToMany('App\Feito', 'feito_persona')
        ->as('info')
        ->withPivot('graduacao');
    }

    public function desvantagensPersona()
    {
        return $this->belongsToMany('App\Desvantagem', 'desvantagem_persona')
        ->as('info')
        ->withPivot('graduacao');
    }

    public function poderesPersona()
    {
        return $this->belongsToMany('App\Poder', 'poder_persona')
        ->as('info')
        ->withPivot(['graduacao', 'custo']);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function poderPersona()
    {
        return $this->hasMany('App\PoderPersona');
    }
}
