import { Controller } from "@hotwired/stimulus";

var controller_default = class extends Controller {
  static targets = ["base", "count", "fullscreen"]

  static values = {
    route: String,
    token: String,
    count: Number,
  }

  mozaic = [];
  shuffled = [];
  activePiece = false
  done = false;

  connect() {
    this.initData();
  }

  initData() {
    fetch(this.routeValue)
      .then(response => response.json()
        .then(data => this.setupMozaic(data))
      );

    this.baseTarget.addEventListener('click', this.checkPiece.bind(this));
    this.fullscreenTarget.addEventListener('click', this.toggleFullscreen.bind(this));
  }

  setupMozaic(data) {
    this.mozaic = data.mozaic;
    this.shuffled = this.shuffle(data.mozaic);

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

  checkPiece(event) {
    if (this.done) {
      return;
    }

    const { target } = event;
    const pieceIndex = [...this.baseTarget.children].indexOf(target);

    if (false === this.activePiece) {
      this.activePiece = pieceIndex;
      target.classList.add('active');
      return;
    }

    if (this.activePiece === pieceIndex) {
      target.classList.remove('active');
      this.activePiece = false;
      return;
    }

    this.swapElements(this.activePiece, pieceIndex);
    this.baseTarget.querySelector('.active').classList.remove('active');
    this.activePiece = false;
    this.renderItems();

    this.countValue++;
    this.areWeDone();
  }

  countValueChanged(currentValue, oldValue) {
    this.countTarget.textContent = currentValue;
  }

  swapElements = (index1, index2) => {
    const temp = this.shuffled[index1];
    this.shuffled[index1] = this.shuffled[index2];
    this.shuffled[index2] = temp;
  };

  areWeDone() {
    const diff = this.mozaic.filter((piece, index) => {
      return piece.image !== this.shuffled[index].image;
    })

    if (0 === diff.length) {
      this.finish()
    }
  }

  finish() {
    this.done = true;
    this.mozaic.forEach((piece, index) => {
      let img = this.baseTarget.childNodes[index];
      this.setStyles(piece, img.style);
    })
  }

  setStyles(piece, style) {
    let prc = this.done ? 0 : 1;

    style.setProperty('left', piece.percent.left + '%');
    style.setProperty('top', piece.percent.top + '%');
    style.setProperty('width', (piece.percent.width - prc) + '%');
    style.setProperty('height', (piece.percent.height - prc) + '%');
  }

  renderItems() {
    if (0 === this.baseTarget.childNodes.length) {
      this.mozaic.forEach((piece, index) => {
        let img = document.createElement('img')
        //
        img.classList.add('mozaic-piece');

        this.setStyles(piece, img.style);
        this.baseTarget.append(img)
      });
    }

    this.mozaic.forEach((piece, index) => {
      let img = this.baseTarget.childNodes[index];
      img.src = this.shuffled[index].image;
    })
  }

  toggleFullscreen(event) {
    this.element.requestFullscreen();
    return false;
  }
};
export {
  controller_default as default
};
