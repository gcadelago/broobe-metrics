<form id="metricsForm" method="post">
    @if ($errors->any())
      <div class="alert alert-danger" id="err">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <div class="row">
        <div class="form-floating col-md-4 col-sm-12 ml-auto gx-1">
            <input
                type="text"
                class="form-control {{ $errors->first('url') ? 'is-invalid' : '' }}"
                id="url"
                name="url"
                value="{{ old('url') }}"
                placeholder="http://example.com"
                required
            />
            <label>{{ __('URL') }}<span class="text-danger">*</span></label>
        </div>
        <div class="form-floating col-md-4 col-sm-12 pl-0 ml-auto gx-1">
            <select
                class="form-select select2 {{ $errors->first('category') ? 'is-invalid' : '' }}"
                id="category"
                name="category"
                aria-label="Floating label select example"
                multiple
                required
            >
                @foreach ($categories as $category)
                    <option {{ old('category' . $category->name) == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                @endforeach
            </select>
            <label>{{ __('Categories') }}<span class="text-danger">*</span></label>
        </div>
        <div class="form-floating col-md-4 col-sm-12 ml-auto gx-1">
            <select
                type="text"
                class="form-control {{ $errors->first('strategy') ? 'is-invalid' : '' }}"
                id="strategy"
                name="strategy"
                value="{{ old('strategy') }}"
                required
            >
                @foreach ($strategies as $strategy)
                    <option {{ old('strategy') == $strategy ? 'selected' : '' }}>{{$strategy->name}}</option>
                @endforeach
            </select>
            <label>{{ __('Strategy') }}<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-3 col-sm-12 ml-auto pt-2">
            <button type="submit" class="btn btn-primary btn-sm" title="Buscar">
            <i class="fas fa-search"></i>{{ __('Get metrics') }}
            </button>
        </div>
    </div>
</form>
