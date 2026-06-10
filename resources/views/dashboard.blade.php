@extends('layouts.app')

@section('content')
<div class="mb-5">
    <h1 class="fw-bold text-dark m-0">Tableau de Bord</h1>
    <p class="text-muted m-0">Suivi analytique et opérationnel des flux de transport</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Clients Actifs</small>
                    <h3 class="fw-bold text-dark m-0 mt-2">{{ $totalClients ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #f3e8ff; color: #6b21a8;"><i class="fas fa-users fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Transit En Douane</small>
                    <h3 class="fw-bold text-dark m-0 mt-2">{{ $dossiersEnCours ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e0f2fe; color: #0369a1;"><i class="fas fa-box-open fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Maintenances</small>
                    <h3 class="fw-bold text-danger m-0 mt-2">{{ $camionsEnPanne ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #fee2e2; color: #dc2626;"><i class="fas fa-tools fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 text-white" style="background-color: #0f172a;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-uppercase fw-semibold" style="color: #c084fc;">Chiffre d'Affaires</small>
                    <h3 class="fw-bold m-0 mt-2">{{ number_format($totalFactures ?? 0, 2, ',', ' ') }} DH</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #6b21a8;"><i class="fas fa-coins fa-lg"></i></div>
            </div>
        </div>
    </div>
</div>
@endsection