<div x-data="{selection: @entangle('selection')}">
  <button x-show="selection.length > 0" x-on:click="$wire.deleteNotifications(selection)" class="ml-4 btn btn-error">{{__('Delete')}}</button>
    @forelse ($notifications as $notif)
                            @if($notif->data['type'] == 'comment')
                            @php
                            $post = \App\Models\Post::where('id', $notif->data['post_id'])->first()
                            @endphp
                
                            <div class="pt-2">
                                <div class="alert alert-info shadow-lg">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-240q-17 0-28.5-11.5T240-280v-80h520v-360h80q17 0 28.5 11.5T880-680v503q0 27-24.5 37.5T812-148l-92-92H280Zm-40-200-92 92q-19 19-43.5 8.5T80-377v-463q0-17 11.5-28.5T120-880h520q17 0 28.5 11.5T680-840v360q0 17-11.5 28.5T640-440H240Z"/></svg>
                                    <div>
                                        <h3 class="font-bold">New comment !</h3>
                                        <div class="text-xs">{{$post->title}} has a new comment !</div>
                                      </div>
                                      <a href="{{route('post.public.view', ['post' => $post->id, 'slug' => $post->slug])}}" class="btn btn-sm">View</a><input x-model="selection" value="{{$notif->id}}" type="checkbox" checked="checked" class="checkbox checkbox-primary" />
                                    </div>
                            </div>
                            @elseif($notif->data['type'] == 'post_deleted')
                            <div class="pt-2">
                                <div class="alert alert-error shadow-lg">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                                    <div>
                                        <h3 class="font-bold">Post rejected</h3>
                                        <div class="text-xs">{{$notif->data['title']}} has been rejected</div>
                                      </div>
                                      <button onclick="modal_{{str_replace('-', '', $notif->id)}}.showModal()" class="btn btn-sm">Details</button><input value="{{$notif->id}}" x-model="selection" type="checkbox" checked="checked" class="checkbox checkbox-primary" />
                                    </div>
                            </div>
                            <dialog id="modal_{{str_replace('-', '', $notif->id)}}" class="modal">
                              <div class="modal-box">
                                <h3 class="font-bold text-lg">Your post {{$notif->data['title']}} has been rejected for this reasons.</h3>
                                <p class="py-4">{{$notif->data['reasons']}}</p>
                                <div class="modal-action">
                                  <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                    <button class="btn">Close</button>
                                  </form>
                                </div>
                              </div>
                            </dialog>
                            @elseif($notif->data['type'] == 'Postvalidated')
                            @php
                            $post = \App\Models\Post::where('id', "=", intval($notif->data['post_id']))->first();
          
                            @endphp
                            <div class="pt-2">
                                <div class="alert alert-success shadow-lg">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-408-86-86q-11-11-28-11t-28 11q-11 11-11 28t11 28l114 114q12 12 28 12t28-12l226-226q11-11 11-28t-11-28q-11-11-28-11t-28 11L424-408Zm56 328q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                                    <div>
                                        <h3 class="font-bold">Post Published</h3>
                                        <div class="text-xs">{{$post->title}} has been published !</div>
                                      </div>
                                      <a href="{{route('post.public.view', ['post' => $post->id, 'slug' => $post->slug])}}" class="btn btn-sm">View</a><input x-model="selection" value="{{$notif->id}}" type="checkbox" checked="checked" class="checkbox checkbox-primary" />
                                    </div>
                            </div>

                            @elseif($notif->data['type'] == 'error')
                            @php
                            $post = \App\Models\Post::where('id', $notif->data['post_id'])->first()
                            @endphp
                            <div class="pt-2">
                                <div class="alert alert-warning shadow-lg">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm400-280q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240Z"/></svg>                                    <div>
                                        <h3 class="font-bold">Error reported !</h3>
                                        <div class="text-xs">Error reported on {{$post->title}}</div>
                                      </div>
                                      <a href="{{route('post.public.view', ['post' => $post->id, 'slug' => $post->slug])}}" class="btn btn-sm">View</a><input x-model="selection" value="{{$notif->id}}" type="checkbox" checked="checked" class="checkbox checkbox-primary" />                                    </div>
                            </div>
                            @endif
                            @empty
                            <div class="pt-2">
                              <div class="alert alert-primary shadow-lg">
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m694-273 142-142q12-12 28-11.5t28 12.5q11 12 11.5 28T892-358L722-188q-12 12-28 12t-28-12l-85-86q-11-11-11.5-27.5T581-330q11-11 28-11t28 11l57 57Zm-454 33-92 92q-19 19-43.5 8.5T80-177v-623q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v240q0 17-11.5 28.5T840-520q-17 0-28.5-11.5T800-560v-240H160v525l46-45h234q17 0 28.5 11.5T480-280q0 17-11.5 28.5T440-240H240Zm-80-80v-480 480Z"/></svg>                                  <div>
                                      <div class="text-xs">{{__('You don\'t have notifications !')}}</div>
                                    </div>
                              </div>
                            </div>
                                
                            @endforelse
</div>
