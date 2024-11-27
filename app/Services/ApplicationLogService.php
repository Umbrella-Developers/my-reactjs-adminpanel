<?php

namespace App\Services;

use App\Traits\ServiceTrait;
use Illuminate\Http\Request;

/**
 * Class ApplicationLogService.
 */
class ApplicationLogService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    /*
        applicationLogIndex is used to fetch logs from the table 
        Also can be filtered by dates and by searchinh for module name.
    */
    public function applicationLogIndex(Request $request, $id = null, $model){   
        $query = $model::query();
        // Search by module name
        if ($request->has('search_module') && $request->search_module) {
            $query->where('module', 'LIKE', '%' . $request->search_module . '%');
        }

        // Date range filtering
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }

        // Pagination (10 items per page, adjust as needed)
        $logs = $query->orderBy('created_at', 'desc')->paginate(100);
         return view('applicationlogs.index', compact('logs'));
    }
    public function applicationLogShow(Request $request, $id, $model){   
        return $this->show($request, $id, $model, $search = false);    
    }
}
