<?php

namespace App\Http\Controllers;

use App\Tournaments;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $user->loadMissing(['roles', 'profile']);
        $profile = $user->profile;
        $isPlayer = $user->hasRole('player');
        $birthDate = null;
        if ($profile && $profile->dob) {
            $date = \DateTime::createFromFormat('!Y-m-d', $profile->dob);
            if ($date && $date->format('Y-m-d') === $profile->dob) {
                $birthDate = $date->format('d/m/Y');
            }
        }
        $promoter = $user->hasRole('promoter');
        $admin = $user->hasRole('admin');
        $tournaments = collect();
        $sections = [];

        if ($promoter) {
            $sections['team'] = ['title' => 'Gestión de equipo', 'cards' => [
                'roster' => ['title' => 'Administrar roster', 'icon' => 'list-alt', 'description' => 'Gestiona los jugadores de tus torneos.', 'url' => route('rosters.index')],
            ]];
        }

        if ($promoter || $admin) {
            // Preserve the same ownership scope as the existing competition screens.
            $tournaments = Tournaments::where('user_id', $user->id)
                ->where('is_deleted', 0)->orderBy('title')->get(['id', 'title', 'slug']);
            $sections['competition'] = ['title' => 'Competencia', 'cards' => [
                'tournaments' => ['title' => 'Torneos', 'icon' => 'trophy', 'description' => 'Consulta y administra tus torneos.', 'url' => route('tournaments.index')],
                'categories' => ['title' => 'Categorías', 'icon' => 'cubes', 'description' => 'Administra las categorías de tus torneos.', 'url' => route('categories.index')],
                'teams' => ['title' => 'Equipos', 'icon' => 'users', 'description' => 'Gestiona equipos por torneo.', 'url' => route('teams.index')],
                'calendar' => ['title' => 'Calendario', 'icon' => 'calendar', 'description' => 'Consulta el calendario de un torneo.', 'calendar' => true],
                'games' => ['title' => 'Partidos', 'icon' => 'futbol-o', 'description' => 'Consulta la programación y captura resultados.', 'url' => route('games.index')],
            ]];
        }

        if ($admin) {
            $catalogs = [];
            foreach (['sports' => 'Deportes', 'stadiums' => 'Estadios', 'states' => 'Estados',
                'municipalities' => 'Municipios', 'categories' => 'Categorías', 'groups' => 'Grupos',
                'actions' => 'Acciones'] as $route => $title) {
                $catalogs[] = ['title' => $title, 'url' => route($route.'.index')];
            }
            $sections['administration'] = ['title' => 'Administración', 'cards' => [
                'users' => ['title' => 'Usuarios', 'icon' => 'user-circle', 'description' => 'Administra usuarios y sus roles.', 'url' => route('users.index')],
                'roles' => ['title' => 'Roles', 'icon' => 'key', 'description' => 'Administra el catálogo de roles.', 'url' => route('roles.index')],
                'catalogs' => ['title' => 'Catálogos', 'icon' => 'th-list', 'description' => 'Accede a los catálogos del sistema.', 'links' => $catalogs],
            ]];
        }

        return view('home', compact('user', 'profile', 'birthDate', 'isPlayer', 'sections', 'tournaments'));
    }
}
