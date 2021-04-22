                    @php ($firstMessage = $messages[0])
                    @if ($showInterlocutor)
                        @php ($interlocutor = $firstMessage->getRelationValue('interlocutor'))
                        <div class="card mt-4 user-message-container">
                            <div class="card-header container-fluid">
                                <img src="{{ $interlocutor->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3" />
                                With <a href="{{ @route('herds.show', $interlocutor->username) }}">{{ $interlocutor->name }}</a>
                            </div>
                    @else
                        <h2>
                            <span class="text-muted">Message history</span>
                        </h2>
                        <div class="card user-message-container">
                    @endif
                            <div class="card-body container-fluid">
                    @foreach($messages as $message)
                        @php ($sender = $message->getRelationValue('sender'))
                        @php ($receiver = $message->getRelationValue('receiver'))

                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="user-message-timestamp">
                                        {{ date('M jS, Y', strtotime($message->created_at)) }}
                                        </span>
                                        <span class="user-message-username">{{ $sender->name }}</span>
                                        <span class="user-message-text"> {!! $message->message !!}</span>
                                    </div>
                                </div>
                    @endforeach
                            </div>
                        </div>
