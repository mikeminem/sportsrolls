@extends('pdf.layout')
@section('title',"Hoja de Anotaciones")

@section('content')

<table class="table" width="100%" padding="5" cellspacing="2" border="0">

    {{-- HEADER 1 --}}
    <tr>
        <td>
             <table class="table" width="100%">
                    <tr>
                        <th width="20%">
                            <img src="{{ asset('images/icoDeporteZacatecas.png') }}" align="center" width=60px">
                        </th>
                        <th align="left">
                            <h3>Hoja de Anotacion </h3>
                            <p>Nombre del torneo</p>
                        </th>
                    </tr>
            </table>

        </td>

    </tr>

    {{-- HEADER 2 --}}
    <tr>
        <td>
              <table class="table" width="100%" padding="5" cellspacing="2" border="0">
                <tr>
                    <td width="50%">
                        <p><b>EQUIPO A:</b> Nombre del equipo A</p>
                    </td>
                    <td width="50%">
                        <p><b>EQUIPO B:</b> Nombre del equipo B</p>
                    </td>
                </tr>

                <tr>
                    <td width="50%">
                        <p><b>COMPETENCIA:</b> Nombre de la competencia</p>
                    </td>
                    <td width="50%">
                        <p><b>FECHA:</b> 20/06/2024 - <b>HORA</b> hh:mm - <b>LOCALIDAD</b> Zacatecas</p>

                    </td>
                </tr>

                 <tr>
                    <table width="100%">
                       <tr>
                            <td width="25%">
                            <p><b>PARTIDO NO:</b> Folio Game</p>
                        </td>

                        <td width="25%">
                            <p><b>ÁRBITRO:</b> Nombre árbitro</p>
                        </td>

                        <td width="25%">
                            <p><b>AUXILIAR 1:</b> Nombre Auxiliar 1</p>
                        </td>

                        <td width="25%">
                            <p><b>AUXILIAR 2:</b> Nombre Auxiliar 2</p>
                        </td>
                        </tr>
                    </table>

                </tr>

            </table>
        </td>

    </tr>

    {{-- TABLE OF PLAYERS --}}
    <tr>
        <td>
           <table width="100%">
                <tr>
                    <td>
                        {{-- TABLE OF PLAYERS TEAM A --}}
                        <table class="table" width="100%" padding="5" cellspacing="2" border="1">
                            <tr>
                                <th colspan="9" align="left">EQUIPO A: NOMBRE DEL EQUIPO</th>
                            </tr>
                            <tr>
                                <th colspan="9" align="left">
                                       {{-- TABLE TIME OUTS AND FOULS --}}
                                    <table class="table" width="100%" padding="5" cellspacing="2" border="0">
                                        <tr>
                                            <th colspan="2" align="left">TIEMPOS MUERTOS Y FALTAS></th>
                                        </tr>
                                        <tr>
                                            <th width="40%">TIEMPOS</th>
                                            <th width="60%">FALTAS DE EQUIPO</th>
                                        </tr>
                                        <tr>
                                            <td align="center">Equipo A</td>
                                            <td></td>
                                        </tr>
                                    </table>

                                    {{-- END TABLE TIME OUTS AND FOULS --}}
                                </th>

                            </tr>
                            <tr>
                                <th width="55%">Nombre del Jugador</th>
                                <th width="5%">No</th>
                                <th width="5%">Entr</th>
                                <th width="5%">1</th>
                                <th width="5%">2</th>
                                <th width="5%">3</th>
                                <th width="5%">4</th>
                                <th width="5%">5</th>
                                <th width="5%"> - </th>

                            </tr>

                                @for ($i = 1; $i <= 12; $i++)
                                <tr>
                                    <td></td>
                                    <td align="center">{{ $i }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endfor

                        </table>
                        {{-- END TABLE PLAYERS TEAM A --}}

                    </td>

                    {{-- points traking section --}}
                    <td width="60%">
                        space for table of points traking
                    </td>


                </tr>

           </table>
        </td>


    </tr>




</table>

@endsection
