@foreach($gastos as $gasto)
    <tr class="tr-active" data-attr="{{$gasto->id_gasto}}">
        <td class="text-start folio-row">{{$gasto->folio}}</td>
        <td class="text-start gasto-row">{{$gasto->concepto_gasto}}</td>
        <td class="text-center importe-row">{{$gasto->importe}}</td>
        <td class="text-center status-row">{{$gasto->status}}</td>
        <td class="text-center fecha-row">{{$gasto->fecha}}</td>
    </tr>
@endforeach