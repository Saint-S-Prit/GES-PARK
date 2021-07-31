import { Component, OnInit } from '@angular/core';
import { Agent } from 'src/app/modele/agent';

@Component({
  selector: 'app-agent',
  templateUrl: './agent.component.html',
  styleUrls: ['./agent.component.css']
})
export class AgentComponent implements OnInit {
  agents:Agent[]=[];

  constructor() { }

  ngOnInit(): void {}


}
