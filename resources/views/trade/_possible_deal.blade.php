
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's double elePHPants you don't have yet:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsToTrade as $elephpant)
                                            @include('trade._elephpant')
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's looking for these ones you have double:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsInterested as $elephpant)
                                            @include('trade._elephpant')
                                        @endforeach
                                    </ul>
                                </div>
                                @if(count($user->messages))
                                <div class="messages-exchanges-container" data-controller="messages">
                                    <div class="alert alert-info md-12 mb-3" data-target="messages.buttonbox">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-primary" data-action="messages#showExchanges">Show history</button>
                                        </div>
                                        <div class="messages-exchanges-infotext">
                                            You have a message history with {{ $user->name }}
                                        </div>
                                    </div>
                                    <div class="messages-exchanges-list md-12 mb-3" data-target="messages.exchanges" style="display: none">
                                        @php ($messages = $user->messages)
                                        @include('messages/_messagesList')
                                    </div>
                                </div>
                                @endif
                                <div data-controller="ping" data-ping-id="{{ $user->id }}">
                                    <div class="message-box alert alert-success mb-0" style="display: none;">🎉 The message was sent to the user! 👏👏</div>
                                    <div class="form-box">
                                        <div class="form-group mb-2">
                                            <textarea name="message" id="" class="form-control" rows="2" placeholder="Hey, just saw you're looking for an elePHPant I have double. Let's trade?" data-target="ping.message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary" data-action="ping#send">Send message to user</button>
                                    </div>
                                </div>
