import React from 'react';
import ReactDOM from 'react-dom';

class Slider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      error: null,
      isLoaded: false,
      releases: [],
      startIndex: 0,
      endIndex: 5
    };
    this.backgroundColors = [
      "#162827",
      "#11403e",
      "#0f1128",
      "#212421",
      "#240d0d",
      "#1b1920"
    ];
    this.slide = this.slide.bind(this);
    this.handleKeyDown = this.handleKeyDown.bind(this);
  }

  componentDidMount() {
    document.addEventListener("keydown", this.handleKeyDown);
    var username = document.URL.split('/').pop();
    fetch("/api/releases/" + username)
      .then(res => res.json())
      .then(
        (result) => {
          let startIndex = Math.floor(Math.random() * result.length - 3) + 3;
          this.setState({
            isLoaded: true,
            releases: result,
            startIndex: startIndex,
            endIndex: startIndex + 5,
            disableLeft: false,
            disableRight: false
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  slide(places) {
    const { startIndex, endIndex, releases } = this.state;

    const releaseCount = releases.length
    let newStartIndex = startIndex + places
    let newEndIndex = endIndex + places
    if ((newStartIndex + 2) === releaseCount) {
      return;
    }

    if (newStartIndex < 0) {
      // never set a negative starting index
      newStartIndex = 0
    }

    let itemsInScope = newEndIndex - newStartIndex

    if (places === -1) { // going left
      if (newStartIndex === 0 && itemsInScope < 3) {
        // we always need at least three items when we are at the beginning
        return;
      }
    }

    if (places === 1) { // going right
      if (newStartIndex > 0 && itemsInScope < 5) {
        newStartIndex--;
      }
    }

    let disableLeft = false;
    let disableRight = false;
    if ((newStartIndex + 3) === releaseCount) {
      disableRight = true;
    }
    if (newStartIndex === 0 && newEndIndex === 3) {
      disableLeft = true;
    }

    this.setState({
      startIndex : newStartIndex,
      endIndex : newEndIndex,
      disableLeft: disableLeft,
      disableRight: disableRight
    })
  }

  handleKeyDown(event) {
    if (event.key === 'ArrowLeft') {
      this.slide(-1);
    }
    else if (event.key === 'ArrowRight') {
      this.slide(1);
    }
  }

  render() {
    const { error, isLoaded, releases, startIndex, endIndex, disableLeft, disableRight } = this.state;

    let addToIndex = 1;
    let albumsShown = endIndex - startIndex;
    if (startIndex === 0) {
      addToIndex += (5 - albumsShown);
    }

    let activeItem = 3 - addToIndex;
    let activeRelease = releases.slice(startIndex, endIndex)[activeItem];

    if (error) {
      return <div>Error: {error.message}</div>;
    } else if (!isLoaded) {
      return <div style={{color: "white"}}>Loading record collection...</div>;
    } else {
      // const body = document.getElementsByTagName('body');
      // body.setAttribute("style", "background-color: " + backgroundColor);

      document.body.style.background = this.backgroundColors[Math.floor(Math.random()*this.backgroundColors.length)];

      return (
        <div className="cards-container">
          <input type="radio" name="slider" id="item-1" />
          <input type="radio" name="slider" id="item-2" />
          <input type="radio" name="slider" id="item-3" defaultChecked={true} />
          <input type="radio" name="slider" id="item-4" />
          <input type="radio" name="slider" id="item-5" />

          <button hidden={disableLeft} className="btn btn-controls btn-controls-left" onClick={this.slide.bind(this, -1)}>
            {"<"}
          </button>
          <div className="cards">
            {releases.slice(startIndex, endIndex).map((release, index) => (
              <label className="cover" htmlFor={"item-" + (index + addToIndex)} id={"song-" + (index + addToIndex)} key={release.id}>
                <img src={ release.basic_information.cover_image } alt="song" />
              </label>
            ))}
          </div>
          <button hidden={disableRight} className="btn btn-controls btn-controls-right" onClick={this.slide.bind(this, 1)}>
            {">"}
          </button>

          <div className="player">
          <button hidden={disableLeft} className="btn btn-controls btn-controls-left" onClick={this.slide.bind(this, -1)}>
            {"<"}
          </button>
            <div className="upper-part">
              <div className="info-area" id="test">
                <label className="song-info" id="song-info-1">
                  <div className="artist">{ activeRelease.artist }</div>
                  <div className="sub-line">
                    <a href={"https://www.discogs.com/release/" + activeRelease.id} className="subtitle" target="_blank">{ activeRelease.basic_information.title }</a>
                  </div>
                </label>
              </div>
            </div>
            <button hidden={disableRight} className="btn btn-controls btn-controls-right" onClick={this.slide.bind(this, 1)}>
            {">"}
          </button>
          </div>

        </div>
      );
    }
  }
}

ReactDOM.render(<Slider />, document.getElementById('root'));
