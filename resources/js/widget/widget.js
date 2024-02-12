import '../bootstrap.js';

import { createApp } from 'vue'
import { state as globalState, emitter } from "../global-state.js";

import Widget from "./Widget.vue";
import axios from "axios";

function initHelpster(appId, params) {
  axios.get(`https://helpster.pics/api/config?app_id=${appId}`)
    .then(response => response.data)
    .then(response => {
      const assets = response.assets;

      for (let asset of assets) {
        const type = asset.type;
        if (type === 'style') {
          const style = document.createElement('link');
          style.setAttribute('rel', 'stylesheet');
          style.setAttribute('href', asset.path);
          document.head.appendChild(style);
        }
      }

      window.helpster = {
        organization: response.organization,
        params: params,
        app_id: appId
      };

      const container = document.createElement('div');
      container.setAttribute('id', 'helpster-widget-container');
      document.body.appendChild(container);

      const app = createApp(Widget);
      app.mount('#helpster-widget-container')
    });
}

window.initHelpster = initHelpster;

const socket = new WebSocket('wss://142.93.168.9:8080');

socket.addEventListener('message', (e) => {
  let data = e.data;
  data = JSON.parse(data);
  const { event, payload } = data;

  emitter.emit('socket:event', event, payload);
});

globalState.socket = socket;
