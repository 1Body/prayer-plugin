class IdeaBox extends React.Component {
 constructor(props) {
 super(props);
 this.state = { data: [] };
 this.loadIdeasFromServer = this.loadIdeasFromServer.bind(this);
 this.handleIdeaSubmit = this.handleIdeaSubmit.bind(this);
 this.handleIdeaDelete = this.handleIdeaDelete.bind(this);
 this.handleIdeaUpdate = this.handleIdeaUpdate.bind(this);
 }
 loadIdeasFromServer() {

   // axios.get(this.props.url)
   axios.get('http://localhost:8888/wp-json/prayer/v1/prayer')
    .then(res => {
      console.log('hi')
      console.log(res.data, 'res.data')
      this.setState({
        data: res.data
      })
    })
    .catch(function (error) {
      console.log('hello error')
      console.log(error);
    });
 }
 handleIdeaSubmit(idea) {
    console.log(idea, 'idea to post')
   let ideas = this.state.data;
   ideas.id = Date.now();
   console.log(ideas, 'ideas')
   let newIdeas = ideas.concat([idea]);
   this.setState({ data: newIdeas });
   axios.post('http://localhost:8888/wp-json/prayer/v1/prayer', idea)
   .catch(err => {
      console.log(idea, 'idea')
      console.error(err, 'error in post');
      this.setState({ data: ideas });
   });
   console.log('this is working')
}
handleIdeaDelete(id) {
axios.delete(`${this.props.url}/${id}`)
.then(res => {
console.log('Idea deleted');
})
.catch(err => {
console.error(err);
});
}
handleIdeaUpdate(id, idea) {
//sends the idea id and new author/text to our api
axios.put(`${this.props.url}/${id}`, idea)
.catch(err => {
console.log(err);
})
}
componentDidMount() {
   this.loadIdeasFromServer();
   setInterval(this.loadIdeasFromServer, this.props.ideaInterval);
}
 render() {
    return (
     <div style={ style.ideaBox }>
     <h2 style={ style.title }>Make a Prayer Request</h2>
     <nav>
         <ul>
            <span style={style.subtitle}> Please make a prayer request below.</span>

         </ul>
      </nav>
         <IdeaForm onIdeaSubmit={ this.handleIdeaSubmit }/>

        <IdeaList
          onIdeaDelete={ this.handleIdeaDelete }
          onIdeaUpdate={ this.handleIdeaUpdate }
          data={ this.state.data }/>


     </div>
     )
 }
}

class IdeaList extends React.Component {

 render() {
 let IdeaNodes = this.props.data.map((idea, i) => {
    return (
        <div style={style.root}>

              <Idea
                style={style.idea}
                name={ idea.name }
                uniqueID={idea['_id']}
                onIdeaDelete={ this.props.onIdeaDelete }
                onIdeaUpdate={ this.props.onIdeaUpdate }
                key={idea['_id']}
                >
               { idea.prayer_request }
              </Idea>

        </div>
            )
    })
 return (
 <div style={ style.IdeaList }>
 { IdeaNodes }
 </div>
 )
 }
}

class IdeaForm extends React.Component {
 constructor(props) {
 super(props);
    this.state = { name: '', email: '', prayer_request: '' };
    this.handleNameChange = this.handleNameChange.bind(this);
    this.handleprayer_requestChange = this.handleprayer_requestChange.bind(this);
    this.handleEmailChange = this.handleEmailChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
 }
 handleNameChange(e) {
    this.setState({ name: e.target.value });
 }
 handleprayer_requestChange(e) {
    this.setState({ prayer_request: e.target.value });
 }
 handleEmailChange(e) {
    this.setState({ email: e.target.value });
 }
 handleSubmit(e) {
    e.preventDefault();
    let name = this.state.name.trim();
    let prayer_request = this.state.prayer_request.trim();
    let email = this.state.email.trim();
    if (!prayer_request || !name || !email) {
       return;
    }
    this.props.onIdeaSubmit({ name: name, email: email, prayer_request: prayer_request });
    this.setState({ name: '', email: '', prayer_request: '' });
 }
 render() {
    return (
   <div id="submit-prayer-form" style={style.submitFormDiv}>
   <form style={ style.ideaForm } onSubmit={ this.handleSubmit }>
You may add your prayer request to our prayer wall using the form below. Once your prayer request is received, we will share it according to your instructions. Feel free to submit as many prayer requests as you like!
    <input
    id="name"
    type='name'
    placeholder='Your name'
    style={ style.ideaFormName}
    value={ this.state.name }
    onChange={ this.handleNameChange } />
    <input
    id="email"
    type='email'
    placeholder='Your Email'
   //  style={ style.ideaFormemail}
    value={ this.state.email }
    onChange={ this.handleEmailChange } />
    <input placeholder="Your Phone"/>
    <select>
        <option value="">Share this</option>
        <option value="">Share this anonymously</option>
        <option value="">Do NOT share this</option>
   </select>
   <input type="checkbox"/>
   <label for="">Email me when someone prays</label>

    <input
    id="prayer_request"
    type='prayer_request'
    placeholder='Prayer Request'
    style={ style.ideaFormprayer_request}
    value={ this.state.prayer_request }
    onChange={ this.handleprayer_requestChange } />
    <input type='submit'
    style={ style.ideaFormPost }
    label="Post"/>
    </form>
    </div>
    )
 }
}

class Idea extends React.Component {
 constructor(props) {
 super(props);
     this.state= {
       toBeUpdated: false,
       name: '',
       email: '',
       prayer_request: ''
   };
 //binding all our functions to this class
 this.deleteIdea = this.deleteIdea.bind(this);
 this.updateIdea = this.updateIdea.bind(this);
 this.handleNameChange = this.handleNameChange.bind(this);
 this.handleprayer_requestChange = this.handleprayer_requestChange.bind(this);
 this.handleEmailChange = this.handleEmailChange.bind(this);
 this.handleIdeaUpdate = this.handleIdeaUpdate.bind(this);
 }
 updateIdea(e) {
    e.preventDefault();
    //brings up the update field when we click on the update link.
    this.setState({ toBeUpdated: !this.state.toBeUpdated });
 }
 handleIdeaUpdate(e) {
    e.preventDefault();
    let id = this.props.uniqueID;
    //if name or prayer_request changed, set it. if not, leave null and our PUT
    //request will ignore it.
    let name = (this.state.name) ? this.state.name : null;
    let prayer_request = (this.state.prayer_request) ? this.state.prayer_request : null;
    let email = (this.state.email) ? this.state.email : null;
    let idea = { name: name, email: email, prayer_request: prayer_request};
    this.props.onIdeaUpdate(id, idea);
    this.setState({
       toBeUpdated: !this.state.toBeUpdated,
       name: '',
       email: '',
       prayer_request: ''
    })
 }
 deleteIdea(e) {
    e.preventDefault();
    let id = this.props.uniqueID;
    this.props.onIdeaDelete(id);
    console.log('oops deleted', id);
 }
 handleprayer_requestChange(e) {
    this.setState({ prayer_request: e.target.value });
 }
 handleNameChange(e) {
    this.setState({ name: e.target.value });
 }
 handleEmailChange(e) {
    this.setState({ email: e.target.value });
 }
 rawMarkup() {
    let rawMarkup = marked(this.props.children.toString());
    return { __html: rawMarkup };
 }
 render() {
    return (

       <div style={ style.idea }>
           <h3>{this.props.name}</h3>
                       <span dangerouslySetInnerHTML={ this.rawMarkup() } />
                       <a style={ style.updateLink } href='#' onClick={ this.updateIdea } label="update">update</a>
                       <a style={ style.deleteLink } href='#' onClick={ this.deleteIdea } label="delete">delete</a>
                       { (this.state.toBeUpdated)
                       ? (<form onSubmit={ this.handleIdeaUpdate }>
                       <input
                       type='prayer_request'
                       placeholder='Update name…'
                       style={ style.ideaFormName }
                       value={ this.state.name }
                       onChange= { this.handleNameChange } />
                       <input
                       type='prayer_request'
                       placeholder='Update your idea…'
                       style= { style.ideaFormprayer_request }
                       value={ this.state.prayer_request }
                       onChange={ this.handleprayer_requestChange } />
                       <input
                       type='submit'
                       style={ style.ideaFormPost }
                       value='Update' />
                       </form>)
                       : null}

       </div>
    )
    }
}
ReactDOM.render(<IdeaBox url='http://localhost:3001/api/ideas' ideaInterval={2000}/>, document.getElementById('quiz'));
