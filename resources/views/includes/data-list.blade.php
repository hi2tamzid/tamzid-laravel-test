<h2 class="text-center mt-5 mb-5">Data List</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Product name</th>
            <th>Quantity in stock</th>
            <th>Price per item</th>
            <th>Datetime submitted</th>
            <th>Total value number</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($existingData))
            @php
                $total = 0;
            @endphp
            @foreach ($existingData as $index => $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>{{ $product['price'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($product['created_at'])->format('d M Y, h:i A') }}</td>
                    <td>{{ $product['quantity'] * $product['price'] }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"
                            data-index="{{ $index }}" data-name="{{ $product['name'] }}"
                            data-quantity="{{ $product['quantity'] }}" data-price="{{ $product['price'] }}">
                            Update
                        </button>
                    </td>
                </tr>
                @php
                    $total += $product['quantity'] * $product['price'];
                @endphp
            @endforeach
            <tr>
                <td colspan="4">Total Value numbers: </td>
                <td colspan="2">{{ $total }}</td>
            </tr>
        @else
            <tr>
                <td class="text-center" colspan="6">No data available.</td>
            </tr>
        @endif
    </tbody>
</table>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    @csrf
                    <input type="hidden" name="index" id="updateIndex">
                    <div class="mb-3">
                        <label for="updateName" class="form-label">Product name:</label>
                        <input type="text" id="updateName" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateQuantity" class="form-label">Quantity in stock:</label>
                        <input type="text" id="updateQuantity" name="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="updatePrice" class="form-label">Price per item:</label>
                        <input type="text" id="updatePrice" name="price" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var updateModal = document.querySelector('#updateModal');
    updateModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var index = button.getAttribute('data-index');
        var name = button.getAttribute('data-name');
        var quantity = button.getAttribute('data-quantity');
        var price = button.getAttribute('data-price');

        var modalIndex = updateModal.querySelector('#updateIndex');
        var modalName = updateModal.querySelector('#updateName');
        var modalQuantity = updateModal.querySelector('#updateQuantity');
        var modalPrice = updateModal.querySelector('#updatePrice');

        modalIndex.value = index;
        modalName.value = name;
        modalQuantity.value = quantity;
        modalPrice.value = price;
    });
    document.querySelector('#updateForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        fetch('{{ route('data.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('#form-response').innerHTML =
                        '<div class="alert alert-success">' + data.message + '</div>';
                    location.reload();
                } else {
                    document.getElementById('#form-response').innerHTML =
                        '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
