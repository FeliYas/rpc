<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionDetalle;
use App\Models\Logo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiquidacionController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::select('id', 'nombre', 'apellido')->orderBy('apellido')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        
        $query = Liquidacion::with('empleado')->orderBy('created_at', 'desc');
        
        // Filtros
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_fin', '<=', $request->fecha_hasta);
        }
        
        $liquidaciones = $query->paginate(15);
        
        return view('dashboard.liquidaciones.index', compact('liquidaciones', 'empleados', 'logo'));
    }
    
    public function create()
    {
        $empleados = Empleado::select('id', 'nombre', 'apellido', 'valor_hora', 'cantidad_horas', 'en_blanco')
                           ->orderBy('apellido')
                           ->get();
        $logo = Logo::where('seccion', 'footer')->first();
        
        return view('dashboard.liquidaciones.create', compact('empleados', 'logo'));
    }
    
    public function edit(Liquidacion $liquidacion)
    {
        $liquidacion->load(['empleado', 'detalles' => function($query) {
            $query->orderBy('fecha');
        }]);
        $logo = Logo::where('seccion', 'footer')->first();
        
        return view('dashboard.liquidaciones.edit', compact('liquidacion', 'logo'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'ano' => 'required|integer|min:2020|max:2030',
            'mes' => 'required|integer|min:1|max:12',
            'semana' => 'required|integer|min:1|max:4',
            'detalles' => 'required|array',
            'detalles.*.fecha' => 'required|date',
            'detalles.*.horas_trabajadas' => 'required|numeric|min:0|max:24',
            'detalles.*.horas_extra' => 'nullable|numeric|min:0|max:24',
            'detalles.*.valor_hora' => 'required|numeric|min:0',
            'detalles.*.premio_dia' => 'nullable|numeric|min:0',
            'detalles.*.estado' => 'required|in:presente,ausente,feriado,vacaciones'
        ]);
        
        DB::beginTransaction();
        
        try {
            $empleado = Empleado::findOrFail($request->empleado_id);
            
            // Calcular fechas de inicio y fin
            $fechas = $this->calcularFechasPeriodo($request->ano, $request->mes, $request->semana, $empleado->en_blanco);
            
            // Crear liquidación
            $liquidacion = Liquidacion::create([
                'empleado_id' => $request->empleado_id,
                'fecha_inicio' => $fechas['inicio'],
                'fecha_fin' => $fechas['fin'],
                'valor_hora_base' => $empleado->valor_hora,
                'total_horas_trabajadas' => 0,
                'total_horas_extra' => 0,
                'total_premios' => 0,
                'subtotal_base' => 0,
                'subtotal_extra' => 0,
                'total_neto' => 0
            ]);
            
            $totalHorasTrabajadas = 0;
            $totalHorasExtra = 0;
            $totalPremios = 0;
            $subtotalBase = 0;
            $subtotalExtra = 0;
            
            // Crear detalles
            foreach ($request->detalles as $detalle) {
                $fecha = Carbon::parse($detalle['fecha']);
                $diaSemana = $this->obtenerDiaSemana($fecha->dayOfWeek);
                
                $horasTrabajadas = floatval($detalle['horas_trabajadas']);
                $horasExtra = floatval($detalle['horas_extra'] ?? 0);
                $valorHora = floatval($detalle['valor_hora']);
                $premioDia = floatval($detalle['premio_dia'] ?? 0);
                $valorHoraExtra = $valorHora * 1.5;
                
                $subtotalNormal = $horasTrabajadas * $valorHora;
                $subtotalExtraDetalle = $horasExtra * $valorHoraExtra;
                $totalDia = $subtotalNormal + $subtotalExtraDetalle + $premioDia;
                
                LiquidacionDetalle::create([
                    'liquidacion_id' => $liquidacion->id,
                    'fecha' => $detalle['fecha'],
                    'dia_semana' => $diaSemana,
                    'estado' => $detalle['estado'],
                    'horas_trabajadas' => $horasTrabajadas,
                    'horas_extra' => $horasExtra,
                    'valor_hora' => $valorHora,
                    'valor_hora_extra' => $valorHoraExtra,
                    'premio_dia' => $premioDia,
                    'subtotal_normal' => $subtotalNormal,
                    'subtotal_extra' => $subtotalExtraDetalle,
                    'total_dia' => $totalDia
                ]);
                
                $totalHorasTrabajadas += $horasTrabajadas;
                $totalHorasExtra += $horasExtra;
                $totalPremios += $premioDia;
                $subtotalBase += $subtotalNormal;
                $subtotalExtra += $subtotalExtraDetalle;
            }
            
            // Actualizar totales de la liquidación
            $liquidacion->update([
                'total_horas_trabajadas' => $totalHorasTrabajadas,
                'total_horas_extra' => $totalHorasExtra,
                'total_premios' => $totalPremios,
                'subtotal_base' => $subtotalBase,
                'subtotal_extra' => $subtotalExtra,
                'total_neto' => $subtotalBase + $subtotalExtra + $totalPremios
            ]);
            
            DB::commit();
            
            return redirect()->route('liquidaciones.dashboard')
                           ->with('success', 'Liquidación creada exitosamente.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error al crear la liquidación: ' . $e->getMessage()]);
        }
    }
    
    public function update(Request $request, Liquidacion $liquidacion)
    {
        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.horas_trabajadas' => 'required|numeric|min:0|max:24',
            'detalles.*.horas_extra' => 'nullable|numeric|min:0|max:24',
            'detalles.*.valor_hora' => 'required|numeric|min:0',
            'detalles.*.premio_dia' => 'nullable|numeric|min:0',
            'detalles.*.estado' => 'required|in:presente,ausente,feriado,vacaciones'
        ]);
        
        DB::beginTransaction();
        
        try {
            $totalHorasTrabajadas = 0;
            $totalHorasExtra = 0;
            $totalPremios = 0;
            $subtotalBase = 0;
            $subtotalExtra = 0;
            
            foreach ($request->detalles as $detalleId => $detalle) {
                $liquidacionDetalle = LiquidacionDetalle::findOrFail($detalleId);
                
                $horasTrabajadas = floatval($detalle['horas_trabajadas']);
                $horasExtra = floatval($detalle['horas_extra'] ?? 0);
                $valorHora = floatval($detalle['valor_hora']);
                $premioDia = floatval($detalle['premio_dia'] ?? 0);
                $valorHoraExtra = $valorHora * 1.5;
                
                $subtotalNormal = $horasTrabajadas * $valorHora;
                $subtotalExtraDetalle = $horasExtra * $valorHoraExtra;
                $totalDia = $subtotalNormal + $subtotalExtraDetalle + $premioDia;
                
                $liquidacionDetalle->update([
                    'estado' => $detalle['estado'],
                    'horas_trabajadas' => $horasTrabajadas,
                    'horas_extra' => $horasExtra,
                    'valor_hora' => $valorHora,
                    'valor_hora_extra' => $valorHoraExtra,
                    'premio_dia' => $premioDia,
                    'subtotal_normal' => $subtotalNormal,
                    'subtotal_extra' => $subtotalExtraDetalle,
                    'total_dia' => $totalDia
                ]);
                
                $totalHorasTrabajadas += $horasTrabajadas;
                $totalHorasExtra += $horasExtra;
                $totalPremios += $premioDia;
                $subtotalBase += $subtotalNormal;
                $subtotalExtra += $subtotalExtraDetalle;
            }
            
            $liquidacion->update([
                'total_horas_trabajadas' => $totalHorasTrabajadas,
                'total_horas_extra' => $totalHorasExtra,
                'total_premios' => $totalPremios,
                'subtotal_base' => $subtotalBase,
                'subtotal_extra' => $subtotalExtra,
                'total_neto' => $subtotalBase + $subtotalExtra + $totalPremios
            ]);
            
            DB::commit();
            
            return redirect()->route('liquidaciones.dashboard')
                           ->with('success', 'Liquidación actualizada exitosamente.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar la liquidación: ' . $e->getMessage()]);
        }
    }
    
    public function destroy(Liquidacion $liquidacion)
    {
        try {
            $liquidacion->delete();
            return redirect()->route('liquidaciones.dashboard')
                           ->with('success', 'Liquidación eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la liquidación: ' . $e->getMessage()]);
        }
    }
    
    public function getDiasLaborales(Request $request)
    {
        $empleado = Empleado::findOrFail($request->empleado_id);
        $fechas = $this->calcularFechasPeriodo($request->ano, $request->mes, $request->semana, $empleado->en_blanco);
        
        $dias = [];
        $fechaActual = Carbon::parse($fechas['inicio']);
        $fechaFin = Carbon::parse($fechas['fin']);
        
        while ($fechaActual->lte($fechaFin)) {
            $dias[] = [
                'fecha' => $fechaActual->format('Y-m-d'),
                'dia_semana' => $this->obtenerDiaSemana($fechaActual->dayOfWeek),
                'dia_nombre' => $fechaActual->format('l'),
                'fecha_formateada' => $fechaActual->format('d/m/Y'),
                'valor_hora' => $empleado->valor_hora,
                'cantidad_horas' => $empleado->cantidad_horas
            ];
            $fechaActual->addDay();
        }
        
        return response()->json($dias);
    }
    
    private function calcularFechasPeriodo($ano, $mes, $semana, $esQuincenal)
    {
        if ($esQuincenal) {
            // Liquidación quincenal
            if ($semana <= 2) {
                // Primera quincena
                $inicio = Carbon::create($ano, $mes, 1);
                $fin = Carbon::create($ano, $mes, 15);
            } else {
                // Segunda quincena
                $inicio = Carbon::create($ano, $mes, 16);
                $fin = Carbon::create($ano, $mes)->endOfMonth();
            }
        } else {
            // Liquidación semanal
            $primerDiaDelMes = Carbon::create($ano, $mes, 1);
            $inicio = $primerDiaDelMes->copy()->addWeeks($semana - 1);
            $fin = $inicio->copy()->addDays(6);
            
            // Ajustar si se sale del mes
            if ($fin->month != $mes) {
                $fin = Carbon::create($ano, $mes)->endOfMonth();
            }
        }
        
        return [
            'inicio' => $inicio->format('Y-m-d'),
            'fin' => $fin->format('Y-m-d')
        ];
    }
    
    private function obtenerDiaSemana($numeroDia)
    {
        $dias = [
            0 => 'domingo',
            1 => 'lunes', 
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado'
        ];
        
        return $dias[$numeroDia];
    }
}
