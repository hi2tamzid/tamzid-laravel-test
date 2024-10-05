<div class="row">
    <div class="col-12">
        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert">
                <ul class="list-group">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <div id="form-response"></div>
        <h2 class="text-center mb-5">Add Product</h2>
        <form id="formSubmit" class="w-25 mx-auto">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Product Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity in stock:</label>
                <input type="text" id="quantity" name="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price per item:</label>
                <input type="text" id="price" name="price" class="form-control" required>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary text-center">Submit</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.querySelector('#formSubmit').addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        fetch('{{ route('form.submit') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let tableBody = document.querySelector('tbody');

                    let lastChild = tableBody.lastElementChild.querySelector("td:last-child");
                    let lastChildValue = Number(lastChild.innerHTML);
                    lastChild.innerHTML = lastChildValue + (data.product.quantity * data.product.price);

                    let newRow = document.createElement('tr');
                    let createdAt = data.product.created_at;
                    let date = new Date(createdAt);
                    let day = String(date.getDate()).padStart(2, '0');
                    let month = date.toLocaleString('en-US', {
                        month: 'short'
                    });
                    let year = date.getFullYear();
                    let hours = date.getHours();
                    let minutes = String(date.getMinutes()).padStart(2, '0');
                    let period = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12;
                    let formattedDate = `${day} ${month} ${year}, ${hours}:${minutes} ${period}`;
                    newRow.innerHTML = `
                <td>${data.product.name}</td>
                <td>${data.product.quantity}</td>
                <td>${data.product.price}</td>
                <td>${formattedDate}</td>
                <td>${data.product.quantity * data.product.price}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"
                        data-index="${data.index}" data-name="${data.product.name}" 
                        data-quantity="${data.product.quantity}" data-price="${data.product.price}">
                        Update
                    </button>
                </td>
            `;
                    if (tableBody.firstChild != null)
                        tableBody.insertBefore(newRow, tableBody.firstChild);
                    else
                        tableBody.innerHTML = newRow;
                    document.querySelector('#form-response').innerHTML =
                        '<div class="alert alert-success">' + data.message + '</div>';
                    this.reset();
                } else {
                    document.querySelector('#form-response').innerHTML =
                        '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
