<x-master>
    <x-slot:title>
        Dashboard
    </x-slot:title>

    @if (session()->has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session()->has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @if (Auth::user()->role == 'customer')
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal"
                        data-target="#createHeroModal">
                        <span><i class="fa-solid fa-plus"></i></span>{{ __(' Create Ticket') }}
                    </button>
                @endif
            </div>

        </div>
    </div>

    <div class="table-responsive small">
        <table class="table table-striped table-sm text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    @if (Auth::user()->role == 'admin')
                        <th scope="col">Ticket Creator</th>
                    @endif
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $item)
                    <tr class="align-middle">
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $item->title }}</td>
                        <td>
                            @if (Auth::user()->role == 'admin')
                        <th scope="col">{{ $item->user->name }}</th>
                @endif
                </td>
                <td>{{ $item->description }}</td>
                <td>
                    @if (Auth::user()->isAdmin())
                        @if ($item->status == 'open')
                            <form action="{{ route('ticket.close', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm link-danger">{{ __('Open') }}</button>
                            </form>
                        @else
                            {{ $item->status }}
                        @endif
                    @else
                        {{ $item->status }}
                    @endif

                </td>

                <td>
                    <button type="button" class="btn btn-sm link-success" data-toggle="modal1"
                        data-target="#showModal{{ $item->id }}">
                        <i class="fa-solid fa-eye fs-5"></i></i>
                    </button>
                </td>


                </tr>
            @empty
                <tr>
                    <td colspan="5">No tickets available!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="createHeroModal" tabindex="-1" aria-labelledby="createHeroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="createHeroModalLabel"><span><i
                                class="fa-solid fa-plus"></i></span>{{ __(' Create New Ticket') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title') }}">
                            @error('title')
                                <small class=" text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" rows="3" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary">{{ __('Create') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @forelse ($tickets as $item)
        <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="showModalLabel{{ $item->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="showModalLabel{{ $item->id }}">
                            {{ $item->title }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card" style="width: auto;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text">{{ $item->description }}</p>
                            </div>
                        </div>

                        {{-- messenger start --}}

                        <!-- Chat Window -->
                        <div class="col-md-9 p-0 d-flex flex-column">
                            <div class="bg-white chat-window p-3">
                                @foreach ($item->responses as $response)
                                    <div class="message @if ($response->user->role == 'admin') text-end @endif">
                                        <span class="sender"><strong>{{ $response->user->name }}
                                                ({{ $response->user->role }})
                                                :</strong></span>
                                        <p>{{ $response->response }}</p>
                                        <small
                                            class="text-muted">{{ $response->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>

                            <!-- Response Input Area -->
                            @if ($item->status != 'closed')
                                <div class="message-input bg-light p-3">
                                    <form action="{{ route('response.store', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <textarea class="form-control" name="response" placeholder="Type a response..." required></textarea>
                                            <button class="btn btn-primary" type="submit">Send</button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    This Ticket is closed and no further responses can be added.
                                </div>
                            @endif
                        </div>
                        {{-- messenger end --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    @empty
    @endforelse


    @push('js')
        <script>
            $(document).ready(function() {
                $('[data-toggle="modal"]').click(function() {
                    var targetModal = $(this).data('target');
                    $(targetModal).modal('show');
                });
            });
            $(document).ready(function() {
                $('[data-toggle="modal1"]').click(function() {
                    var targetModal = $(this).data('target');
                    $(targetModal).modal('show');
                });
            });
            $(document).ready(function() {
                $('[data-toggle="modal2"]').click(function() {
                    var targetModal = $(this).data('target');
                    $(targetModal).modal('show');
                });
            });
        </script>
    @endpush

</x-master>


{{--
Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus quae saepe eos hic porro temporibus incidunt magnam nam voluptates quisquam, rerum necessitatibus libero animi officia aspernatur! Aliquam, nam expedita! Fugiat recusandae sunt autem eos at? Inventore asperiores quidem, aut veritatis dolor ad magnam, expedita temporibus error maxime eos iste eum.
 --}}
