const Hopin = require('hopin');

class SiteServer {
  constructor() {
    this._hop = new Hopin({
      relativePath: __dirname,
    });
  }

  start(portNumber) {
    return this._hop.startServer(portNumber);
  }

  stop() {
    return this._hop.stopServer();
  }
}

module.exports = new SiteServer();
