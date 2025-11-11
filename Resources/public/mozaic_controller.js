import { Controller } from "@hotwired/stimulus";

var controller_default = class extends Controller {
  static targets = ["base"]

  static values = {
    route: String,
    token: String
  }

  mozaic = [];
  shuffled = [];
  activePiece = false
  done = false;

  connect() {
    this.initData();
    console.log(this.baseTarget);
  }

  initData() {
    fetch(this.routeValue)
      .then(response => response.json()
        .then(data => this.setupMozaic(data))
      );
  }

  setupMozaic(data) {
    this.mozaic = data.mozaic;
    this.shuffled = this.shuffle(data.mozaic);
    for (let i in this.shuffled) {
      data.mozaic[i]['shuffled'] = this.shuffled[i];
    }

    this.renderItems();
  }

  shuffle(a) {
    let array = [];
    let i;
    for (i in a) {
      array.push(a[i]);
    }
    let copy = [], n = array.length;

    while (n) {
      let i = Math.floor(Math.random() * array.length);

      if (i in array) {
        copy.push(array[i]);
        delete array[i];
        n--;
      }
    }

    return copy;
  }

  checkPiece(piece) {
    if (this.done) {
      return;
    }
    if (false === this.activePiece) {
      this.activePiece = piece;
      // this.renderItems();
      return;
    }

    // let activeShuffle = this.activePiece.shuffled;
    // Vue.set(this.activePiece, 'shuffled', piece.shuffled);
    // piece.shuffled = activeShuffle;
    //
    // this.clickCount++;
    // this.activePiece = false;
    // this.areWeDone();
  }

  isActivePiece(piece) {
    if (false === this.activePiece) {
      return false;
    }

    return this.activePiece.x === piece.x && this.activePiece.y === piece.y;
  }

  setStyles(piece, style) {
    let prc = this.done ? 0 : 1;

    style.setProperty('left', piece.percent.left + '%');
    style.setProperty('top', piece.percent.top + '%');
    style.setProperty('width', (piece.percent.width - prc) + '%');
    style.setProperty('height', (piece.percent.height - prc) + '%');
  }

  renderItems() {
    this.mozaic.forEach(piece => {
      let img = document.createElement('img')
      img.src = piece.shuffled.image;
      img.classList.add('mozaic-piece');
      if (this.isActivePiece(piece)) {
        img.classList.add('active');
      }
      img.addEventListener('click', () => {
        this.checkPiece(piece);
      })

      this.setStyles(piece, img.style);

      this.baseTarget.append(img)
    })
  }
};
export {
  controller_default as default
};
