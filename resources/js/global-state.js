import { createApp, ref } from 'vue'
import {TinyEmitter} from "tiny-emitter";

const state = createApp({  });
state.config.globalProperties.$bus = state;

const emitter = new TinyEmitter();

const emit = (event, payload) => {
  state.socket.send(JSON.stringify({ event, payload }));
}

const user = ref(null);

export { state, emitter, emit, user };
