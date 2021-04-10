                    @php ($previousCouple = '0-0')
                    @foreach($messages as $message)
                        @php ($sender = $message->getRelationValue('sender'))
                        @php ($receiver = $message->getRelationValue('receiver'))
                        @if (Auth::id() == $sender->id)
                            @php ($leftUser = $receiver)
                            @php ($rightUser = $sender)
                        @else
                            @php ($rightUser = $receiver)
                            @php ($leftUser = $sender)
                        @endif
                        @php ($currentCouple = $leftUser->id.'-'.$rightUser->id)

                        @if ($previousCouple != $currentCouple)
                        <div class="card mt-4">
                            <div class="card-header container-fuild">
                                <div class="row">
                                <div class="card-sender col-md-7">
                                    <img src="{{ $leftUser->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3" />
                                    From: <a href="{{ @route('herds.show', $leftUser->username) }}">{{ $leftUser->name }}</a>
                                </div>
                                <div class="card-receiver col-md-5 float-right">
                                    To: <a href="{{ @route('herds.show', $rightUser->username) }}">{{ $rightUser->name }}</a>
                                    <img src="{{ $rightUser->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3" />
                                </div>
                                </div>
                            </div>
                        @else
                        <div class="card">
                        @endif
                            <div class="card-body container-fluid user-message-container">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <p class="user-message-timestamp">
                                            {{ date('M jS, Y', strtotime($message->created_at)) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                    @if (Auth::id() != $sender->id)
                                        <p class="user-message-text">
                                        {!! nl2br($message->message) !!}
                                        </p>
                                    @endif
                                    </div>
                                    <div class="col-md-5 float-right">
                                    @if (Auth::id() == $sender->id)
                                        <p class="user-message-text">
                                        {!! nl2br($message->message) !!}
                                        </p>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($previousCouple != $currentCouple)
                        @endif
                        @php ($previousCouple = $currentCouple)
                    @endforeach
