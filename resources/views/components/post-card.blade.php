<div class="card h-full bg-base-100 shadow-xl">
                                    <figure><img class="object-cover w-full h-48" src="{{url('storage/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" alt="Shoes" />
                                      <div class="right-0 top-0 absolute pin-t pin-l ">
                                        @auth
                                    <livewire:favorite-button :post=$post :user=Auth::user() lazy/>
                                    @endauth
                                    </div>
                                  </figure>
                                    <div class="card-body">
                                      <div class="flex flex-items">
                                      <span class="badge badge-md bg-{{$post->course->color}} text-white">{{$post->course->name}}</span>
                                      @if($post->pinned)<span class="badge badge-md badge-neutral">Pinned</span>
                                      @endif
                                      </div>
                                        <h2 class="card-title">{{$post->title}}</h2>
                                        <p>{{$post->description}}</p>
                                        <div class="card-actions items-center justify-center flex">
                                            <div class="items-center justify-center flex min-h-[6rem] min-w-[18rem] max-w-4xl flex-wrap gap-2">
                                                <ul class="menu menu-horizontal bg-base-200 rounded-box">
                                                  <li>
            
                                                        <a wire:navigate href="{{
                                                          route('post.public.view', [$post->slug, $post->id])
                                                        }}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                                                        </a>
                                                    </li>
                                                    @foreach($post->files as $file)
                                                    @if ($file->type == 'primary light')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM80-440q-17 0-28.5-11.5T40-480q0-17 11.5-28.5T80-520h80q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440H80Zm720 0q-17 0-28.5-11.5T760-480q0-17 11.5-28.5T800-520h80q17 0 28.5 11.5T920-480q0 17-11.5 28.5T880-440h-80ZM480-760q-17 0-28.5-11.5T440-800v-80q0-17 11.5-28.5T480-920q17 0 28.5 11.5T520-880v80q0 17-11.5 28.5T480-760Zm0 720q-17 0-28.5-11.5T440-80v-80q0-17 11.5-28.5T480-200q17 0 28.5 11.5T520-160v80q0 17-11.5 28.5T480-40ZM226-678l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226-678Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734-282l43 42q12 11 11.5 28T777-183q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678-734l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183-183q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282-226l-42 43q-11 12-28 11.5T183-183Zm297-297Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @elseif($file->type == 'primary dark')
                                                    <li>
                                                        <a href="{{url('storage/'.$file->file_path.'')}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                    @if($post->cards)
                                                    <li>
                                                        <a>
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                                <path d="m608-368 46-166-142-98-46 166 142 98ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Zm-28-75 302-110-179-490-301 110 178 490Zm62-300Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($post->quizlet_url !== NULL)
                                                    <li>
                                                        <a href="{{'https://quizlet.com/'.$post->quizlet_url.''}}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="24" height="24">
                                                                <path d="M 11 4 C 7.14 4 4 7.14 4 11 L 4 39 C 4 42.86 7.14 46 11 46 L 39 46 C 42.86 46 46 42.86 46 39 L 46 11 C 46 7.14 42.86 4 39 4 L 11 4 z M 25 11 C 32.72 11 39 17.28 39 25 C 39 28.231 37.889922 31.201266 36.044922 33.572266 L 39.611328 37.851562 C 39.786328 38.063563 39.823031 38.348656 39.707031 38.597656 C 39.590031 38.846656 39.347266 39 39.072266 39 L 33.96875 39 C 33.52375 39 33.103406 38.804891 32.816406 38.462891 L 32.816406 38.460938 L 31.792969 37.232422 C 29.778969 38.355422 27.464 39 25 39 C 17.28 39 11 32.72 11 25 C 11 17.28 17.28 11 25 11 z M 25 17 C 20.589 17 17 20.589 17 25 C 17 29.411 20.589 33 25 33 C 25.996 33 26.947125 32.809609 27.828125 32.474609 L 23.390625 27.148438 C 23.214625 26.937438 23.176969 26.651344 23.292969 26.402344 C 23.409969 26.153344 23.652734 26 23.927734 26 L 29.03125 26 C 29.47625 26 29.896594 26.195109 30.183594 26.537109 L 32.048828 28.777344 C 32.655828 27.651344 33 26.365 33 25 C 33 20.589 29.411 17 25 17 z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>