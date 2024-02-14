
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
                                <div data-controller="ping" data-ping-id="{{ $user->id }}">
                                    <div class="message-box alert alert-success mb-0" style="display: none;">🎉 The message was sent to the user! 👏👏</div>
                                    <div class="form-box">
                                        <div class="form-group mb-2">
                                            <textarea name="message" id="" class="form-control" rows="2" placeholder="Hey, just saw you're looking for an elePHPant I have double. Let's trade?" data-target="ping.message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary" data-action="ping#send">Send message to user</button>
                                    </div>
                                </div>
