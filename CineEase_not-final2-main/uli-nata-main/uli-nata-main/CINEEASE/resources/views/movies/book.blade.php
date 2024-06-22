<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('movies.book', ['id' => $movie->id]) }}" class="nav-link">Booking Page</a>

        <link rel="stylesheet" href="{{ asset('css/userdash.css') }}">
        <link rel="stylesheet" href="{{ asset('css/book.css') }}">
    </x-slot>

    <div class="main-content">
        <div class="movies-container2">
            <h2 class="section-title">Book Your Ticket Here</h2>
            <div class="movie-item2">
                <img src="{{ asset('storage/' . $movie->poster) }}" alt="Movie Poster" class="poster">
                <div class="details2">
                    <h3 class="title">{{ $movie->title }}</h3>
                    <p class="amount">Price: {{ $movie->amount }} pesos</p>
                </div>
            </div>

            <div class="table-container-wrapper">
                <form action="{{ route('movies.reserve') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">

                    <div class="table-container">
                        <table>
                            <tr>
                                <td>Theater</td>
                                <td>Performance Art Theater</td>
                            </tr>
                            <tr>
                                <td>No. of Seats</td>
                                <td>
                                    <div class="input-wrapper">
                                        <input type="number" id="quantity" name="quantity" min="1" max="{{ $movie->seats_available }}" required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Seat Arrangement</td>
                                <td>
                                    <div id="seatSelections">
                                        <!-- Seat selection fields will be added here dynamically -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <td>
                                    <span id="totalAmount">0.00</span> pesos
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="proceed-button">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var movieCostValue = {{ $movie->amount }};
        var reservedSeats = @json($reservedSeats ?? []);
        var confirmedSeats = @json($confirmedSeats ?? []);

        document.getElementById('quantity').addEventListener('input', function() {
            var quantity = parseInt(this.value);
            var seatSelections = document.getElementById('seatSelections');
            var totalAmount = quantity * movieCostValue;

            seatSelections.innerHTML = ''; // Clear existing seat selection fields

            for (var i = 0; i < quantity; i++) {
                var selectWrapper = document.createElement('div');
                selectWrapper.className = 'select-wrapper';

                var select = document.createElement('select');
                select.name = 'seatArrangement[]';

                @foreach (range('A', 'L') as $row)
                    @foreach (range(1, 8) as $seat)
                        var seatValue = '{{ $row . $seat }}';
                        var option = document.createElement('option');
                        option.value = seatValue;
                        option.text = seatValue;

                        if (reservedSeats.includes(seatValue)) {
                            option.disabled = true;
                            option.text += ' (Reserved)';
                        }

                        if (confirmedSeats.includes(seatValue)) {
                            option.disabled = true;
                            option.text += ' (Confirmed)';
                        }

                        select.appendChild(option);
                    @endforeach
                @endforeach

                selectWrapper.appendChild(select);
                seatSelections.appendChild(selectWrapper);
            }

            document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
        });

        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            var quantity = parseInt(document.getElementById('quantity').value);
            var seatSelections = document.querySelectorAll('#seatSelections select');

            if (seatSelections.length !== quantity) {
                event.preventDefault();
                alert('Please select the appropriate number of seats for the quantity specified.');
            }
        });
    </script>
</x-app-layout>
