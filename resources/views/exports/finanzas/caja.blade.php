<table>
    <thead>
        <tr>
            <th>Hora</th>
            <th>DNI</th>
            <th>Pasajero</th>
            <th>Detalle</th>
            <th>Desc.</th>
            <th>Anti.</th>
            <th>Total</th>
            <th>Pagos</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
        <tr>
          <td>{{date('H:i', strtotime($venta->created_at))}}</td>
          <td>{{$venta->cuit}}</td>
          <td>{{ucwords($venta->razon_social)}}</td>
          <td>{{$venta->detalle}}</td>
          <td>${{pesosargentinos($venta->descuento)}}</td>
          <td>${{pesosargentinos($venta->anticipo)}}</td>
          <td>${{pesosargentinos($venta->total)}}</td>
          <td>
            @if(count($venta->abonos) > 0)
                @foreach($venta->abonos as $abono)
                {{$abono->medio_pago->nombre}}: {{$abono->moneda->simbolo}}{{pesosargentinos($abono->importe)}}  @if(!$loop->last) / @endif 
                @endforeach
            @else
            Sin pago
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
</table>