<!DOCTYPE html>
<html>
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {line-height:1.0 !important;}
        thead {color:white; background-color: #B0B3CA}
        tbody {color:black;}
        tfoot {color:red;}
                table, th, td {
                border: 0px solid black;
                }

                .table{
                    margin-bottom: 0rem !important;
                }
    </style>

</head>
<body>
    <div class="container-fluet">
        <div class ="row"></div>

            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col-md-12"></th>
                    </tr>
                </thead>
            </table>
            <table class="table ">
                <tbody>
                    <tr>
                        <td  border="0" WIDTH="320px">
                            <br />
                                <img alt="Logo" style=" width:230px; height:180px;" src="https://erp.cetrux.com/web/binary/company_logo">
                        </td >
                        <td border="0" >
                                <b><h4>{{ $xmldetalle->Emisor->NombreComercial  }}</h4></b>
                                 {{ $xmldetalle->Emisor->Nombre }}<br />
                                 {{ $xmldetalle->Emisor->Identificacion->Numero  }}<br />
                                  {{ $xmldetalle->Emisor->Ubicacion->OtrasSenas }}<br />
                                  @php

                                  $num1 = $xmldetalle->Emisor->Telefono->NumTelefono ;
                                  $num2 = $xmldetalle->Emisor->Fax->NumTelefono ;

                                  $numeroTelefononew = '';

                                  if(trim($num1) != trim($num2)){

                                        $numeroTelefononew = trim($num1) . ' / ' . trim($num2);

                                    }
                                    else
                                    {
                                        $numeroTelefononew = trim($num1);
                                    }
                                     $receptoNumeronew = '';
                                     $num3 = $xmldetalle->Receptor->Telefono->NumTelefono ;
                                     $num4 = $xmldetalle->Receptor->Fax->NumTelefono ;

                                     if(trim($num3) != trim($num1))   {
                                        if(trim($num3) != trim($num2)){
                                            $receptoNumeronew = trim($num3);
                                        }
                                     }

                                     if(trim($num4) != trim($num1))   {
                                        if(trim($num4) != trim($num2)){
                                            if(trim($num4) != trim($num3)){
                                                $receptoNumeronew = $receptoNumeronew . ' / ' .trim($num4);
                                            }
                                        }
                                     }

                                  @endphp
                                  {{ $numeroTelefononew }}<br />
                                 {{ $xmldetalle->Emisor->CorreoElectronico  }}
                        </td>
                    </tr>
            </table>
            <table class="table ">
                    <thead>
                        <tr>
                            <th scope="col-md-9">
                                {{ $tipoDocumento }} No.
                            </th>
                            <th scope="col-md-3">
                                 {{ $xmldetalle->NumeroConsecutivo }}
                            </th>
                        </tr>
                    </thead>
                     <tbody>

                            <tr>

                                <td>
                                    <b>Cliente: </b > <br />
                                        {{ $xmldetalle->Receptor->Nombre  }} <br />
                                         {{ $xmldetalle->Receptor->Identificacion->Numero  }} <br />
                                        {{ $receptoNumeronew  }}<br />
                                     {{ $xmldetalle->Receptor->CorreoElectronico  }}
                                </td>
                                <td>

								@php
								$resultadomediopago = 'Otros (multiples tipos de pago)';
								if($xmldetalle->MedioPago == '01'){
									$resultadomediopago = 'Efectivo';
								}
								else{
									if($xmldetalle->MedioPago == '02'){
										$resultadomediopago = 'Tarjeta de credito';
									}
									else{
										if($xmldetalle->MedioPago == '03'){
											$resultadomediopago = 'Cheque';
										}
										else
										{
											if($xmldetalle->MedioPago == '04'){
												$resultadomediopago = 'Transferencia bancaria';
											}
											else{
												if($xmldetalle->MedioPago == '05'){
													$resultadomediopago = 'Recaudado por terceros';
												}
											}
										}
									}
								}

								@endphp

                                    <b> Fecha Emisión: </b> {{substr($xmldetalle->FechaEmision,0,10)}}<br />
                                     Medio de pago: {{ $resultadomediopago }} <br />
                                    @php
                                        $contado = $xmldetalle->CondicionVenta;
                                        if($contado == '01')
                                        {
                                            $contado = 'Contado';
                                        }

                                        $tipoCambioMoneda = $xmldetalle->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                                        if($tipoCambioMoneda == null){
                                            $tipoCambioMoneda = 'CRC';
                                        }
                                    @endphp
                                    Condición venta: {{ $contado }}<br />
                                    Plazo: {{ $xmldetalle->PlazoCredito }}<br />
                                    Moneda: {{ $tipoCambioMoneda }} <br />
                                    Tipo de cambio: {{ $tipoCambioMoneda  == 'CRC'?  0 : $xmldetalle->ResumenFactura->CodigoTipoMoneda->TipoCambio }}

                                </td>
                            </tr>

                    </tbody>
                </table>
                    @php
                    $signomoneda = "¢";
                        if($tipoCambioMoneda  != 'CRC')
                        {
                            $signomoneda = "USD";
                        }
                    @endphp
                <table class="table  table-bordered">
                        <thead>
                          <tr>
                            <th scope="col" style="WIDTH: 5%;">#</th>
                            <th scope="col" >DETALLE</th>
                            <th scope="col" style="WIDTH: 10%;">CANTIDAD</th>
                            <th scope="col" style="WIDTH: 5%;">UNIDAD</th>
                            <th scope="col" style="WIDTH: 15%;">PRECIO UNIT</th>
                            <th scope="col" style="WIDTH: 15%;">IMPUESTO</th>
                            <th scope="col" style="WIDTH: 15%;">TOTAL</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($xmldetalle->DetalleServicio->LineaDetalle as $aceptacion)
                                    <tr>
                                         <th scope="row"  style=" border-right: 0px;  border-left: 0px;">{{ $aceptacion->NumeroLinea }}</th>
                                         <td  style=" border-right: 0px;  border-left: 0px;">{{ $aceptacion->Detalle }}</td>
                                         <td  style=" border-right: 0px;  border-left: 0px;">{{ $aceptacion->Cantidad }}</td>
                                         <td  style=" border-right: 0px;  border-left: 0px;">{{ $aceptacion->UnidadMedida }}</td>
                                         <td  style=" border-right: 0px;  border-left: 0px;">{{ $signomoneda }} {{ round(((float)$aceptacion->PrecioUnitario),2) }}</td>
                                         <td  style=" border-right: 0px;  border-left: 0px;">
                                            @php
                                            $impustoTemp = 0;
                                            if($aceptacion->Impuesto != null)
                                            {
                                                $impustoTemp = $aceptacion->Impuesto->Tarifa;
                                            }
                                            @endphp
                                               <center>
                                                    {{ round(((float)$impustoTemp),2) }}%
                                               </center>

                                         </td>
                                         <td  style=" border-right: 0px;  border-left: 0px;">{{ $signomoneda }} {{ round(((float)$aceptacion->MontoTotalLinea),2) }}</td>
                                    </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" style=" border:0px;"  ></td>
                                <td colspan="1" style=" border-right: 0px; ">
                                        SUB TOTAL<br />
                                        DESCUENTO<br />
                                        GRAVADO<br />
                                        EXENTO<br />
                                         IMPUESTO
                                </td>
                                <td colspan="1"  style="text-align:right; border-right: 0px;  border-left: 0px;">
                                       {{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalVentaNeta),2) }}<br />
                                       {{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalDescuentos),2) }}<br />
                                       {{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalGravado),2) }}<br />
                                       {{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalExento),2) }}<br />
                                       {{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalImpuesto),2) }}
                                </td>
                            </tr>
                             <tr>
                                <td colspan="5" style=" border:0px;"  ></td>
                                <td colspan="1" style=" border-right: 0px; "><h5><b>TOTAL</b></td>
                                <td colspan="1" style="text-align:right; border-right: 0px;  border-left: 0px;"> <b>{{ $signomoneda }} {{ round(((float)$xmldetalle->ResumenFactura->TotalComprobante),2) }}</b></h5></td>
                            </tr>

                        </tbody>
                </table>

        <center> <b> Clave: </b> {{ $xmldetalle->Clave }}</center>
        <center><h5> Autorización mediante la Resolución No.{{ $xmldetalle->Normativa->NumeroResolucion  }} / {{ $xmldetalle->Normativa->FechaResolucion  }}</h5></center>
        <center><h5> Documento electrónico emitido por <a href="cetrux.com"> cetrux.com</a></h5></center>
    </div>
</body>

</html>
