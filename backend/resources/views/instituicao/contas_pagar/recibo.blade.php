@if(!empty($texto))
{!! $texto !!}

@else
<div class="row">
    <div class="col-md-12 row align-items-center">
        <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
        <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
        <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
    </div>
</div>

<hr class="hr-line-dashed">

<div class="row">
    <div class="col-md-12 row align-items-center">
        <h2 class='lead col-sm bg-secondary text-center text-white'>Recibo</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
      <br>
      <p style="font-size:25px;">
          O(a) @if(!empty($conta->paciente)) paciente: <b>{{strtoupper($conta->paciente->nome)}}</b> @elseif(!empty($conta->prestador)) profissional: <b>{{strtoupper($conta->prestador->nome)}}</b> @elseif(!empty($conta->fornecedor)) fornecedor: <b>{{strtoupper($conta->fornecedor->nome)}}</b> @endif  recebeu R$ {{number_format($conta->valor_pago, 2, ',', '.')}} <b>({{App\Support\Outros::valorPorExtenso($conta->valor_pago)}})</b>, referente {{strtoupper($conta->descricao)}}, em  {{\Carbon\Carbon::parse($conta->data_pago)->format('d/m/Y')}}
      </p>

      <br>
      <br>

      <p>
        <hr style="width: 50%; height: 5px; text-align:center" />
        <div  class="col-sm-12 text-center"><label>{{$instituicao->nome}} - {{$instituicao->cnpj}}</label></div>
      </p>
    </div>
</div>

<hr class="hr-line-dashed">

<div class="row">
  <div class="col-md-12 row align-items-center">
      <h2 class='lead col-sm bg-secondary text-center text-white'>Recibo</h3>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <br>
    <p style="font-size:25px;">
      O(a) @if(!empty($conta->paciente)) paciente: <b>{{strtoupper($conta->paciente->nome)}}</b> @elseif(!empty($conta->prestador)) profissional: <b>{{strtoupper($conta->prestador->nome)}}</b> @elseif(!empty($conta->fornecedor)) fornecedor: <b>{{strtoupper($conta->fornecedor->nome)}}</b> @endif  recebeu R$ {{number_format($conta->valor_pago, 2, ',', '.')}} <b>({{App\Support\Outros::valorPorExtenso($conta->valor_pago)}})</b>, referente {{strtoupper($conta->descricao)}}, em  {{\Carbon\Carbon::parse($conta->data_pago)->format('d/m/Y')}}
    </p>

    <br>
    <br>

    <p class="text-center col-md-12">
      <hr style="width: 50%; height: 5px; text-align:center" />
      <div  class="col-sm-12 text-center"><label>{{$instituicao->nome}} - {{$instituicao->cnpj}}</label></div>
    </p>
  </div>
</div>
@endif