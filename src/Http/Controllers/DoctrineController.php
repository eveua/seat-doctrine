<?php /** @noinspection PhpUnused */

namespace GreyZmeem\Seat\Doctrine\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Seat\Eveapi\Models\Sde\InvType;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use GreyZmeem\Seat\Doctrine\Models\Doctrine;
use GreyZmeem\Seat\Doctrine\Models\Fitting;

const LOW_SLOT_EFFECT_ID = 11;
const HIGH_SLOT_EFFECT_ID = 12;
const MED_SLOT_EFFECT_ID = 13;
const SUBSYSTEM_EFFECT_ID = 3772;
const RIG_SLOT_EFFECT_ID = 2663;
const BANDWIDTH_ATTRIBUTE_ID = 1272;

class DoctrineCreateRequestValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['name' => 'required|string'];
    }
}

class DoctrineUpdateRequestValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'fittings' => 'required|array',
            'fittings.*' => 'integer',
        ];
    }
}

class FittingCreateRequestValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eft' => 'required|string',
            'next' => 'nullable|string',
        ];
    }
}

class FittingUpdateRequestValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}

class DoctrineController extends Controller
{
    // *********************************************************************************************
    // Doctrines
    // *********************************************************************************************

    /**
     * Render doctrines list.
     */
    public function doctrineList()
    {
        $doctrines = [];
        foreach (Doctrine::all() as $doctrine) {
            $fittings = [];
            foreach ($doctrine->fittings()->get() as $fitting) {
                $fittings[$fitting->ship] = [
                    'id' => $fitting->id,
                    'ship' => $fitting->ship,
                    'shipID' => $fitting->shipID,
                ];
            }
            $doctrines[] = [
                'id' => $doctrine->id,
                'name' => $doctrine->name,
                'fittings' => (array)$fittings,
            ];
        }
        return view('doctrine::doctrineList', compact('doctrines'));
    }

    /**
     * Retrieve fitting details.
     */
    private function getDoctrineDetails($id): array
    {
        $instance = Doctrine::findOrFail($id);
        $fittings = [];
        foreach ($instance->fittings()->get() as $fitting) {
            $fittings[] = [
                'id' => $fitting->id,
                'name' => $fitting->name,
                'ship' => $fitting->ship,
                'shipID' => $fitting->shipID,
                'description' => $fitting->description,
                'descriptionShort' => $this->shorten($fitting->description, 128),
            ];
        }
        $doctrine = [
            'id' => $instance->id,
            'name' => $instance->name,
            'description' => $instance->description,
            'fittings' => $fittings,
        ];
        return ['instance' => $instance, 'doctrine' => $doctrine];
    }

    /**
     * Render doctrine details.
     */
    public function doctrineDetail($id)
    {
        $doctrine = $this->getDoctrineDetails($id)['doctrine'];
        return view('doctrine::doctrineDetail', compact('doctrine'));
    }

    /**
     * Render doctrine edit page.
     */
    public function doctrineEdit($id) {
        $doctrine = $this->getDoctrineDetails($id)['doctrine'];
        return view('doctrine::doctrineEdit', compact('doctrine'));
    }

    /**
     * Update doctrine details.
     */
    public function doctrineUpdate(DoctrineUpdateRequestValidation $request, $id) {
        $instance = Doctrine::findOrFail($id);
        $instance->name = $request->name;
        $instance->description = $request->description ?? '';
        $instance->save();
        $instance->fittings()->detach();
        $instance->fittings()->attach($request->fittings);
        return redirect()->route('doctrine.doctrineDetail', ['id' => $instance->id]);
    }

    /**
     * Create new doctrine.
     */
    public function doctrineCreate(DoctrineCreateRequestValidation $request)
    {
        $doctrine = new Doctrine();
        $doctrine->name = $request->name;
        $doctrine->description = '';
        $doctrine->save();
        return redirect()->route('doctrine.doctrineDetail', ['id' => $doctrine->id]);
    }

    /**
     * Delete doctrine;
     */
    public function doctrineDelete($id)
    {
        Doctrine::where('id', $id)->delete();
        return redirect()->route('doctrine.doctrineList');
    }

    // *********************************************************************************************
    // Fittings
    // *********************************************************************************************

    /**
     * Render fitting list.
     */
    public function fittingList(Request $request)
    {
        $fittings = [];
        $q = strtolower($request->query('q', ''));
        foreach (Fitting::all() as $fitting) {
            $name = strtolower($fitting->name);
            $ship = strtolower($fitting->ship);
            if (!$q || (strpos($name, $q) !== false || strpos($ship, $q) !== false)) {
                $fittings[] = [
                    'id' => $fitting->id,
                    'name' => $fitting->name,
                    'ship' => $fitting->ship,
                    'shipID' => $fitting->shipID,
                ];
            }
        }
        return $request->query('format') === 'json'
            ? response()->json($fittings)
            : view('doctrine::fittingList', compact('fittings'));
    }

    /**
     * Retrieve fitting details.
     */
    private function getFittingDetails($id): array
    {
        $instance = Fitting::findOrFail($id);
        $invType = InvType::where('typeID', $instance->shipID)->first();
        $fitting = [
            'id' => $instance->id,
            'name' => $instance->name,
            'ship' => $invType ? $invType->typeName : '- Unknown -',
            'shipID' => $instance->shipID,
            'fit' => $instance->fit,
            'description' => $instance->description,
            'doctrines' => [],
        ];
        return ['instance' => $instance, 'fitting' => $fitting];
    }

    /**
     * Render fitting details.
     */
    public function fittingDetail($id)
    {
        $fitting = $this->getFittingDetails($id)['fitting'];
        return view('doctrine::fittingDetail', compact('fitting'));
    }

    /**
     * Render fitting edit page.
     */
    public function fittingEdit($id) {
        $fitting = $this->getFittingDetails($id)['fitting'];
        return view('doctrine::fittingEdit', compact('fitting'));
    }

    /**
     * Update fitting details.
     */
    public function fittingUpdate(FittingUpdateRequestValidation $request, $id) {
        $instance = Fitting::findOrFail($id);
        $instance->name = $request->name;
        $instance->description = $request->description ?? '';
        $instance->save();
        return redirect()->route('doctrine.fittingDetail', ['id' => $instance->id]);
    }

    /**
     * Create new fitting.
     */
    public function fittingCreate(FittingCreateRequestValidation $request)
    {
        $result = $this->parseFitting($request->eft);
        if (array_key_exists('error', $result)) {
            return redirect()->back()->with('error', $result['error']);
        }
        $fitting = new Fitting();
        $fitting->name = $result['name'];
        $fitting->ship = $result['ship'];
        $fitting->shipID = $result['shipID'];
        $fitting->fit = $result['fit'];
        $fitting->save();
        return $request->next === 'list'
            ? redirect()->route('doctrine.fittingList')
            : redirect()->route('doctrine.fittingEdit', ['id' => $fitting->id]);
    }

    /**
     * Delete fitting;
     */
    public function fittingDelete($id)
    {
        Fitting::where('id', $id)->delete();
        return redirect()->route('doctrine.fittingList');
    }

    // *********************************************************************************************
    // Utility
    // *********************************************************************************************

    /**
     * Parse EFT fitting.
     */
    private function parseFitting($eft): array
    {
        $ship = '';
        $name = '';
        $fit = [
            'eft' => $eft,
            'cargo' => [],
            'drones' => [],
            'slotHigh' => [],
            'slotMed' => [],
            'slotLow' => [],
            'slotRig' => [],
            'slotSub' => [],
        ];

        $lineNumber = 0;
        $modules = [];
        $cargoOrDrones = [];
        foreach (preg_split('/((\r?\n)|(\r\n?))/', $eft) as $line) {
            $lineNumber += 1;
            $line = trim($line);

            if (strlen($line) === 0) {
                continue;
            }

            if (preg_match('/^\[.*]$/', $line)) {
                // It's either ship type name or an empty module slot.
                if (preg_match('/^\[(.+),(.+)]$/', $line, $matches)) {
                    if ($ship != '') {
                        return ['error' => "Line ${lineNumber}: duplicate fitting header"];
                    }
                    $ship = trim($matches[1]);
                    $name = trim($matches[2]);
                } elseif (!preg_match('/^\[Empty .+ slot]$/', $line)) {
                    return ['error' => "Line ${lineNumber}: wrongly formatted string"];
                }
                continue;
            }

            if (preg_match('/^(.+) x(\d+)$/', $line, $matches)) {
                $cargoOrDrones[] = ['name' => $matches[1], 'count' => intval($matches[2])];
                continue;
            }

            // Fitted module.
            $modules[] = $line;
        }

        $shipInvType = InvType::where('typeName', $ship)->first();
        if (empty($shipInvType)) {
            return ['error' => "Unknown ship type: {$ship}"];
        } else {
            $shipID = $shipInvType->typeID;
        }

        foreach ($modules as $module) {
            $moduleInvType = InvType::where('typeName', $module)->first();
            if (empty($moduleInvType)) {
                return ['error' => "Unknown module type: {$module}"];
            }
            $typeID = $moduleInvType->typeID;
            $effects = DB::table('dgmTypeEffects')
                ->where('typeID', $typeID)
                ->pluck('effectID')
                ->toArray();
            if (in_array(LOW_SLOT_EFFECT_ID, $effects)) {
                $slotName = 'slotLow';
            } elseif (in_array(MED_SLOT_EFFECT_ID, $effects)) {
                $slotName = 'slotMed';
            } elseif (in_array(HIGH_SLOT_EFFECT_ID, $effects)) {
                $slotName = 'slotHigh';
            } elseif (in_array(SUBSYSTEM_EFFECT_ID, $effects)) {
                $slotName = 'slotSub';
            } elseif (in_array(RIG_SLOT_EFFECT_ID, $effects)) {
                $slotName = 'slotRig';
            } else {
                return ['error' => "Failed to determine module slot: {$module}"];
            }
            $fit[$slotName][] = ['name' => $module, 'typeID' => $typeID];
        }

        foreach ($cargoOrDrones as $cod) {
            $moduleInvType = InvType::where('typeName', $cod['name'])->first();
            if (empty($moduleInvType)) {
                return ['error' => "Unknown module type: {$cod['name']}"];
            }
            $typeID = $moduleInvType->typeID;
            $attributes = DB::table('dgmTypeAttributes')
                ->where('typeID', $typeID)
                ->pluck('attributeID')
                ->toArray();
            $key = 'cargo';
            if (in_array(BANDWIDTH_ATTRIBUTE_ID, $attributes)) {
                $key = 'drones';
            }
            $fit[$key][] = [
                'name' => $cod['name'],
                'count' => $cod['count'],
                'typeID' => $typeID,
            ];
        }

        return [
            'name' => $name,
            'ship' => $ship,
            'shipID' => $shipID,
            'fit' => $fit,
        ];
    }

    private function shorten($text, $length): string
    {
        return strlen($text) > $length
            ? substr($text, 0, $length) . '...'
            : $text;
    }
}
