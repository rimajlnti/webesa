<form method="GET" action="{{ route('sales-orders.index') }}">
   

    <!-- Baris input dan select filter -->
    <div class="form-row row gx-2">
        <div class="col-md-3 mb-3">
            <input type="text" name="customer" placeholder="Customer" value="{{ request('customer') }}" class="form-control" />
        </div>
        <div class="col-md-3 mb-3">
            <input type="text" name="SO" placeholder="SO" value="{{ request('SO') }}" class="form-control" />
        </div>
        <div class="col-md-3 mb-3">
            <input type="text" name="CustPO" placeholder="PO No" value="{{ request('CustPO') }}" class="form-control" />
        </div>
        <div class="col-md-3 mb-3">
            <select name="year" class="form-control">
                <option value="">-- Filter by Year --</option>
                @foreach(range(now()->year, 2020) as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <select name="delivered_status" class="form-control">
                <option value="">-- Delivered Status --</option>
                <option value="Delivered" {{ request('delivered_status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Ready to Ship" {{ request('delivered_status') == 'Ready to Ship' ? 'selected' : '' }}>Ready to Ship</option>
                <option value="Indent" {{ request('delivered_status') == 'Indent' ? 'selected' : '' }}>Indent</option>
            </select>
        </div>
         </div>
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>
    
</form>
