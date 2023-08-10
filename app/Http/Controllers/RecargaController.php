<?php

namespace App\Http\Controllers;

use App\Models\Recarga;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class RecargaController extends Controller
{

    public function index()
    {
        return Recarga::all();
    }

    public function store(Request $request)
    {
        // Accede a los datos del JSON recibido
        $data = $request->json()->all();
        $request->validate([
            'amount' => 'required',
            'titleTicket' => 'required',
            'phone' => 'required',
            'terminal' => 'required',
            'telcelid' => 'required',
            'responsetime' => 'required',
        ]);
        //dd($request->all());
        // Crea y guarda el nuevo registro en la base de datos
        $recarga = new Recarga;
        $recarga->monto = $data['amount'];
        switch ($data['titleTicket']) {
            case 'Venta paquete':
                $recarga->FK_tipo_recarga = 1;
                break;
            case 'Recarga de tiempo aire':
                $recarga->FK_tipo_recarga = 2;
                break;
        }
        $recarga->telefono = $data['phone'];
        switch ($data['terminal']) {
            case '460288':
                $recarga->FK_terminal = 1;
                break;
        }
        $recarga->FK_compania = $data['telcelid'];
        $recarga->fecha = $data['responsetime'];

        $recarga->save();
        $this->Imprimir($data);
        return response()->json($recarga, 201);

    }

    public function Imprimir($data)
    {
        // Crear una instancia del conector de impresión de Windows
        $connector = new WindowsPrintConnector("POS58 Printer");

        // Crear una instancia de la impresora
        $printer = new Printer($connector);

        // Realizar las operaciones de impresión
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        //$printer->setFontSize(2, 2);
        $printer->text("Center Accesories\n");
        $printer->text("Hidalgo #151, Ario de Rosales\n");
        //$printer->text(date('d-m-Y') . "  " . date('H:i:s') . "\n");       
        $printer->text("TICKET DE COMPRA\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("Concepto: " . $data['titleTicket'] . "\n");
        $printer->text("Numero: " . $data['phone'] . "\n");
        $printer->text("Monto: $" . number_format($data['amount'], 2, ".", ",") . "\n");
        $printer->text("Fecha: " . $data['responsetime'] . '\n');
        $printer->text("Terminal: " . $data['terminal'] . '\n');
        $printer->text("Estatus: OK" . '\n');
        $printer->text("\n");
        
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Gracias por su compra :)\n");
        $printer->cut();

        // Cerrar la conexión de impresión
        $printer->close();
    }

    public function show(Recarga $recarga)
    {
        return $recarga;
    }


    public function update(Request $request, Recarga $recarga)
    {
        //
    }


    public function destroy(Recarga $recarga)
    {
        //
    }
}