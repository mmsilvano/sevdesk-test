{@extends(backpack_view('blank'))

@php
  $defaultPageLength = $crud->getDefaultPageLength();
  $xColumns = $crud->columns();
@endphp

@section('header')
  <section class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </section>
@endsection

@section('content')
  <div class="row">
    <div class="{{ $crud->getListContentClass() }}">
      <div class="row mb-0">
        <div class="col-sm-6">
          <div class="d-print-none {{ $crud->hasAccess('create') ? 'with-border' : '' }}">
            @include('crud::inc.button_stack', ['stack' => 'top'])
          </div>
        </div>
        <div class="col-sm-6">
          <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed">
          <thead>
            <tr>
              @foreach ($crud->columns() as $column)
                <th data-orderable="false" data-priority="{{ $loop->index }}" data-visible-in-table="{{ var_export($column['visibleInTable'] ?? true) }}" data-can-be-visible-in-table="true" data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}" data-visible-in-export="{{ var_export($column['visibleInExport'] ?? true) }}" data-force-export="{{ var_export($column['forceExport'] ?? false) }}">
                  {!! $column['label'] !!}
                </th>
              @endforeach
              <th data-orderable="false" data-priority="{{ $crud->columns()->count() }}" data-visible-in-table="false" data-visible-in-modal="false" data-visible-in-export="false" data-force-export="false">
                {{ trans('backpack::crud.actions') }}
              </th>
            </tr>
          </thead>
          <tbody>
            @if($entries && $entries->count() > 0)
              @foreach($entries as $k => $entry)
                <tr data-entry-id="{{ $entry->id }}">
                  @foreach($crud->columns() as $column)
                    <td>
                      @php
                        $column_name = $column['name'];
                        $value = data_get($entry, $column_name) ?? '';
                        
                        // Format the value based on column type
                        if($column['type'] == 'number' && isset($column['prefix'])) {
                            $value = $column['prefix'] . number_format($value, 2);
                        } elseif($column_name == 'valueDate' && $value) {
                            $value = date('Y-m-d', strtotime($value));
                        }
                      @endphp
                      {{ $value }}
                    </td>
                  @endforeach
                  <td>
                    @if ($crud->hasAccess('show'))
                      <a href="{{ url($crud->route.'/'.$entry->id.'/show') }}" class="btn btn-sm btn-link"><i class="la la-eye"></i> {{ trans('backpack::crud.preview') }}</a>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="{{ count($crud->columns()) + 1 }}" class="text-center">
                  No data available
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection}