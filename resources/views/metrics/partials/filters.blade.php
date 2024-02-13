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
    <div class="row d-flex justify-content-center">
        <div class="form-floating col-md-3 col-sm-12 ml-auto gx-1 m-1">
            <input
                type="text"
                class="form-control {{ $errors->first('url') ? 'is-invalid' : '' }}"
                id="url"
                name="url"
                value="{{ old('url') }}"
                maxlength="255"
                pattern="^(https?:\/\/).*$"
                required
            />
            <label>{{ __('URL') }}<span class="text-danger">*</span></label>
            @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <span class="text-muted">{{ __('Example') }}: https://www.google.com</span>
        </div>
        <div class="form-floating col-md-3 col-sm-12 pl-0 ml-auto gx-1 m-1">
            <select
                class="form-select select2 {{ $errors->first('category') ? 'is-invalid' : '' }}"
                id="category"
                name="category"
                aria-label="Floating label select example"
                multiple
                required
            >
                @foreach ($categories as $category)
                    <option class="option" value="{{$category->name}}">{{ __($category->name)}}</option>
                @endforeach
            </select>
            <label>{{ __('Categories') }}<span class="text-danger">*</span></label>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating col-md-3 col-sm-12 ml-auto gx-1 m-1">
            <select
                type="text"
                class="form-control select2 {{ $errors->first('strategy') ? 'is-invalid' : '' }}"
                id="strategy"
                name="strategy"
                value="{{ old('strategy') }}"
                data-minimum-results-for-search="Infinity"
                required
            >
                <option selected hidden disabled></option>
                @foreach ($strategies as $strategy)
                    <option {{ old('strategy') == $strategy ? 'selected' : '' }}>{{$strategy->name}}</option>
                @endforeach
            </select>
            <label>{{ __('Strategy') }}<span class="text-danger">*</span></label>
            @error('strategy')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 col-sm-12 ml-auto p-1">
            <button type="submit" class="button btn btn-outline-primary btn-lg" id="searchMetrics">
            <i class="fas fa-search"></i>{{ __('Get metrics') }}
            </button>
        </div>
    </div>
</form>
