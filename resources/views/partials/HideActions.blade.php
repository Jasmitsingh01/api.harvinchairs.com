{{-- <div class="text-nowrap text-theme-color">
    <a class="text-theme-color" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        <i class="fa-solid fa-eye"></i>
    </a>

    <a class="text-theme-color" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="text-theme-color border-0 bg-transparent px-0" ><i class="fa-solid fa-trash-can"></i> </button>
    </form>
</div> --}}

