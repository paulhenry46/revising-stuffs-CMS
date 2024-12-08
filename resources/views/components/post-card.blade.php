<div class="card @if(!request()->routeIs('post.public.view')) h-full @endif dark:bg-base-200 bg-@isset($type){{str_replace('500', '100',$post->type->color)}}@else{{str_replace('500', '100',$post->course->color)}}@endif">
                                    <figure>
                                        @if(file_exists('../storage/app/public/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png'))

                                        <img class="object-cover w-full h-48" src="{{url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" alt="Thumbnail of the post" />
                                      @else
                                      <div class="object-cover w-full h-48"  >

                                      <div class="flex h-72 flex-col items-center justify-center gap-6  p-10 text-center [text-wrap:balance]">
                                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0 0v-560 560Zm80-80h400q12 0 18-11t-2-21L586-459q-6-8-16-8t-16 8L450-320l-74-99q-6-8-16-8t-16 8l-80 107q-8 10-2 21t18 11Z"/></svg>        <div>{{__('The thumbnail will be here soon.')}}</div> 
    </div>
                                      </div>

                                        @endif
                                        <div class="right-0 top-0 absolute pin-t pin-l ">
                                        @auth
                                    <livewire:favorite-button wire:key="fav_{{$post->id}}" :post=$post :user=Auth::user() lazy/>
                                    @endauth
                                    </div>
                                    <div class="left-0 top-0 absolute pin-t pin-l ">
                                    <livewire:like wire:key="like_{{$post->id}}" :post=$post lazy/>
                                    </div>
                                  </figure>
                                    <div class="card-body">
                                      
                                        <h2 class="card-title">{{$post->title}}</h2>
                                        @if($description === true)
                                        <p>{{$post->description}}</p>
                                        @endif
                                        
                                        <div class="card-actions items-center justify-center flex">
                                            <div class="items-center justify-center flex min-h-[6rem] min-w-[18rem] max-w-4xl flex-wrap gap-2">
                                                <ul class="menu menu-horizontal bg-base-100 rounded-box">
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
                                                        <a wire:navigate href="{{
                                                          route('post.public.cards.show', [$post->slug, $post->id])
                                                        }}">
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
                                                        
                                        <div class="flex flex-items">
                                        @isset($type)
                                        <a class="badge badge-md bg-{{$post->type->color}} text-white">{{$post->type->name}}</a>
                                        @else
                                        <a wire:navigate href="{{route('post.public.courseView', ['curriculum_chosen' => $post->level->curriculum->slug, 'level_chosen' => $post->level->slug, 'course_chosen' => $post->course->slug])}}" class="badge badge-md bg-{{$post->course->color}} text-white">{{$post->course->name}}</a>
                                        @endisset
                                        @if($post->pinned)<span class="ml-1 badge badge-md badge-neutral">{{__('Pinned')}}</span>
                                        @endif
                                        @if($showGroup === true)
                                        @if($post->group_id == 2)
                                        <span class="ml-1 badge badge-md badge-neutral"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="17" viewBox="0 -960 960 960" width="24">
                                  <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z" />
                                </svg> {{__('Public')}}</span>
                                        @elseif($post->group_id == 1)
                                        <span class="ml-1 badge badge-md badge-neutral"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="17" viewBox="0 -960 960 960" width="24">
                                  <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z" />
                                </svg>{{__('Private')}}</span>
                                        @else
                                        <span class="ml-1 badge badge-md badge-neutral"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="17" viewBox="0 -960 960 960" width="24">
                                  <path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z" />
                                </svg> {{$post->group->name}}
                                        @endif
                                        @endif
                                      </div>
                                    </div>
                                </div>