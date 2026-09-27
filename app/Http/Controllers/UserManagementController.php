<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagedUserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->filtered($request)
            ->withCount(['jobs', 'comments'])
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return response()->json([
            'users' => ManagedUserResource::collection($users->items()),
            'total' => $users->total(),
            'page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }

    private function filtered(Request $request): Builder
    {
        $query = User::query();
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $query->where(function (Builder $inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (in_array((string) $request->query('role'), array_keys(User::ROLE_RANKS), true)) {
            $query->where('role', $request->query('role'));
        }

        return $query;
    }
}
